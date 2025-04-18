<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerCreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Only Admin and Employee roles can access these routes
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee')), 403);
            return $next($request);
        });
    }
    
    public function index()
    {
        // Get customers with 'Customer' role
        $customers = User::whereHas('roles', function($query) {
                $query->where('name', 'Customer');
            })
            ->orderBy('name')
            ->get();

        return view('customer-credit.index', compact('customers'));
    }

    public function edit(User $customer)
    {
        // Ensure the user being edited is a customer
        abort_unless($customer->hasRole('Customer'), 404);
        
        return view('customer-credit.edit', compact('customer'));
    }
    
    public function update(Request $request, User $customer)
    {
        // Ensure the user being edited is a customer
        abort_unless($customer->hasRole('Customer'), 404);
        
        $validated = $request->validate([
            'credit' => 'required|numeric|min:0'
        ]);
        
        // Method 1: Eloquent
        $customer->credit = $validated['credit'];
        $saved = $customer->save();
    
        if (!$saved) {
            return back()->with('error', 'Update failed!');
        }
    
        return redirect()->route('customer_credit')
            ->with('success', "Credit updated to ".number_format($customer->fresh()->credit, 2));
    }

    public function reset(User $customer)
    {
        // Ensure the user being edited is a customer
        abort_unless($customer->hasRole('Customer'), 404);
        
        $customer->credit = 0;
        $saved = $customer->save();
    
        if (!$saved) {
            return back()->with('error', 'Reset failed!');
        }
    
        return redirect()->route('customer_credit')
            ->with('success', "{$customer->name}'s credit reset to 0");
    }
}