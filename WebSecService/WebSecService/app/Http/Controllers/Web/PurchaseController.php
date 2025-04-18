<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // app/Http/Controllers/PurchaseController.php
public function store(Request $request, Product $product)
{
    // Check product availability
    if ($product->stock < 1) {
        return back()->with('error', 'This product is out of stock');
    }

    $user = auth()->user();
    
    if ($user->credit < $product->price) {
        return back()->with('error', 'Insufficient credit');
    }
    
    try {
        DB::transaction(function () use ($user, $product) {
            // Deduct credit
            $user->decrement('credit', $product->price);
            
            // Record purchase
            $user->boughtProducts()->attach($product->id, [
                'bought_at' => now(),
                'price_at_purchase' => $product->price,
                'status' => 'completed'
            ]);
            
            // Reduce stock by 1
            $product->decreaseStock();
            
            // Record transaction
            $user->transactions()->create([
                'amount' => -$product->price,
                'description' => "Purchase: {$product->name}",
                'type' => 'purchase'
            ]);
        });
        
        return redirect()->route('purchases.index')
               ->with('success', 'Purchase completed!');
    } catch (\Exception $e) {
        return back()->with('error', 'Purchase failed: '.$e->getMessage());
    }
}
}
