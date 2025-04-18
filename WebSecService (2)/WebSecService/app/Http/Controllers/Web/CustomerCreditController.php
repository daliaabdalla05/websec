<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerCreditController extends Controller
{
    public function index()
    {
        // Get customers with 'customer' role
        $customers = User::whereHas('roles', function($query) {
                $query->where('name', 'customer');
            })
            ->orderBy('name')
            ->get();

        return view('customer-credit.index', compact('customers'));
    }

    public function edit(User $customer)
    {
      
        
        return view('customer-credit.edit', compact('customer'));
    }



    
   
    
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'credit' => 'required|numeric|min:0'
        ]);
    
        // Method 1: Eloquent
        $customer->credit = $validated['credit'];
        $saved = $customer->save();
    
        // Method 2: Alternative
        // $saved = DB::table('users')
        //     ->where('id', $customer->id)
        //     ->update(['credit' => $validated['credit']]);
    
        if (!$saved) {
            return back()->with('error', 'Update failed!');
        }
    
        return redirect()->route('customer_credit')
            ->with('success', "Credit updated to ".number_format($customer->fresh()->credit, 2));
    
    
            return redirect()->route('customer_credit')
                ->with('success', "{$customer->name}'s credit updated to ".number_format($validated['credit'], 2));
        }
    }
