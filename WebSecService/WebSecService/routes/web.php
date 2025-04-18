<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\CustomerCreditController;
use App\Http\Controllers\Web\EmployeeController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');



Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');
// Customer Credit Routes
Route::middleware(['auth'])->group(function() {
    // Main index page
    Route::get('/customer-credit', [CustomerCreditController::class, 'index'])
        ->name('customer_credit');
        
    // Edit form
    Route::get('/customer-credit/edit/{customer}', [CustomerCreditController::class, 'edit'])
        ->name('customer_credit.edit');
        
    // Update action
    Route::put('/customer-credit/update/{customer}', [CustomerCreditController::class, 'update'])
        ->name('customer_credit.update');
});

Route::post('/products/{product}/purchase', [ProductsController::class, 'purchase'])
    ->name('products.purchase')
    ->middleware('auth');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/insufficient-credit', function () {
    return view('errors.insufficient_balance');
})->name('insufficient.credit')->middleware('auth');

// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/products/bought-list', [ProductsController::class, 'boughtProducts'])
         ->name('products.bought');
});


Route::middleware(['auth', 'can:manage_employees'])->group(function () {
    Route::get('/admin/employees', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('employees.store');
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



