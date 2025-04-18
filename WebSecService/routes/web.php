<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\CustomerCreditController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Auth\VerificationController;

// Authentication routes
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended(config('app.url'));
    }

    if ($request->user()->markEmailAsVerified()) {
        event(new \Illuminate\Auth\Events\Verified($request->user()));
    }

    return redirect()->intended(config('app.url').'?verified=1');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended(config('app.url'));
    }

    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function() {
    // User routes
    Route::get('users', [UsersController::class, 'list'])->name('users');
    Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
    Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
    Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
    Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
    Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
    Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

    // Customer Credit Routes
    Route::prefix('customer-credit')->group(function() {
        // Main index page
        Route::get('/', [CustomerCreditController::class, 'index'])
            ->name('customer_credit');
            
        // Edit form
        Route::get('/edit/{customer}', [CustomerCreditController::class, 'edit'])
            ->name('customer_credit.edit');
            
        // Update action
        Route::put('/update/{customer}', [CustomerCreditController::class, 'update'])
            ->name('customer_credit.update');
        
        // Reset credit
        Route::post('/reset/{customer}', [CustomerCreditController::class, 'reset'])
            ->name('customer_credit.reset');
    });

    // Product routes (view for everyone, manage for employees/admin)
    Route::get('products', [ProductsController::class, 'list'])->name('products_list');
    
    // Product management (Employee/Admin only)
    Route::middleware(['can:manage_products'])->group(function() {
        Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
        Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
        Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
    });
    
    // Product purchase (Customer only)
    Route::post('/products/{product}/purchase', [ProductsController::class, 'purchase'])
        ->name('products.purchase')
        ->middleware('can:purchase_products');
    
    // Product refund (Customer only)
    Route::post('/purchases/{purchase}/refund', [ProductsController::class, 'refund'])
        ->name('purchases.refund')
        ->middleware('can:purchase_products');
    
    // Bought products list
    Route::get('/products/bought-list', [ProductsController::class, 'boughtProducts'])
        ->name('products.bought');
        
    // Insufficient credit page
    Route::get('/insufficient-credit', function () {
        return view('errors.insufficient_balance');
    })->name('insufficient.credit');
    
    // Employee management (Admin only)
    Route::middleware(['can:manage_employees'])->group(function () {
        Route::get('/admin/employees', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('employees.store');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Homepage
Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

// Add this route for testing
Route::get('/test-verification', function () {
    // Create a test user
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    // Send verification email
    $user->sendEmailVerificationNotification();

    return 'Verification email sent to ' . $user->email;
});

// Add this protected route for testing
Route::get('/protected-test', function () {
    return 'If you can see this, your email is verified!';
})->middleware(['auth', 'verified']);



