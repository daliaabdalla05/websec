<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase; 
use App\Http\Controllers\Web\PurchaseController;


class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");
		$query->when($request->inventory !== null, function($q) use ($request) {
			return $q->where('inventory_count', '>', 0);
		});

		$query->when($request->keywords, 
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		// Debugging statements
		
		\Log::info('Number of products fetched: ' . $products->count());
		\Log::info('Filters applied:', [
			'inventory' => $request->inventory,
			'keywords' => $request->keywords,
			'min_price' => $request->min_price,
			'max_price' => $request->max_price,
			'order_by' => $request->order_by,
		]);

		return view('products.list', compact('products'));
	}

public function boughtProducts()
{
    // Eager load the products with their details
    $purchases = auth()->user()->boughtProducts()->get();

                  
                  
    return view('products.bought-list', compact('purchases'));
}

public function refund(Request $request, $purchaseId)
{
    $user = auth()->user();
    
    // Check if user is a Customer
    abort_unless($user->hasRole('Customer'), 403);
    
    // Find the purchase in user_bought_products table
    $purchase = DB::table('user_bought_products')
        ->where('id', $purchaseId)
        ->where('user_id', $user->id)
        ->first();
    
    if (!$purchase) {
        return back()->with('error', 'Purchase not found or does not belong to you.');
    }
    
    // Get the product
    $product = Product::find($purchase->product_id);
    
    if (!$product) {
        return back()->with('error', 'Product not found.');
    }
    
    // Check if purchase is eligible for refund (you might want to add more conditions)
    // For example, only allow refunds within 24 hours
    $purchaseTime = new \DateTime($purchase->bought_at);
    $now = new \DateTime();
    $diff = $purchaseTime->diff($now);
    
    if ($diff->days > 1) {
        return back()->with('error', 'Refund period has expired (24 hours).');
    }
    
    DB::transaction(function () use ($user, $product, $purchase) {
        // Refund the credit to the user
        $user->increment('credit', $purchase->price_at_purchase);
        
        // Increase the product inventory
        $product->increment('inventory_count');
        
        // If product was marked as unavailable due to 0 inventory, make it available again
        if ($product->inventory_count > 0 && !$product->is_available) {
            $product->update(['is_available' => true]);
        }
        
        // Update the purchase status to refunded
        DB::table('user_bought_products')
            ->where('id', $purchase->id)
            ->update(['status' => 'refunded']);
    });
    
    return back()->with('success', 'Product refunded successfully. Your credit has been updated.');
}

	public function purchase(Product $product)
	{
		$user = auth()->user();
		
		// Check if user is a Customer
		abort_unless($user->hasRole('Customer'), 403);
		
		// Check if user has enough credit
		if ($user->credit < $product->price) {
			return redirect()->route('insufficient.credit')
				   ->with('product', $product);
		}
		
		// Check if product is available and in stock
		if (!$product->is_available || $product->inventory_count <= 0) {
			return back()->with('error', 'This product is no longer available.');
		}
	
		DB::transaction(function () use ($user, $product) {
			// Decrease user's credit
			$user->decrement('credit', $product->price);
			
			// Create purchase record
			$purchase = Purchase::create([
				'user_id' => $user->id,
				'product_id' => $product->id,
				'price' => $product->price,
				'remaining_credit' => $user->credit
			]);
			
			// Add to user_bought_products table with timestamp
			$user->boughtProducts()->attach($product->id, [
				'bought_at' => now(),
				'price_at_purchase' => $product->price,
				'status' => 'completed'
			]);
			
			// Reduce inventory count
			$product->reduceInventory();
		});
	
		return back()->with('success', 'Product purchased successfully!');
	}


	public function edit(Request $request, Product $product = null) {
		// Check if user is authorized (Admin or Employee)
		abort_unless(auth()->user() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee')), 403);

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {
		// Check if user is authorized (Admin or Employee)
		abort_unless(auth()->user() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee')), 403);

		$this->validate($request, [
	        'code' => ['required', 'string', 'max:32'],
	        'name' => ['required', 'string', 'max:128'],
	        'model' => ['required', 'string', 'max:256'],
	        'description' => ['required', 'string', 'max:1024'],
	        'price' => ['required', 'numeric'],
			'inventory_count' => ['required', 'integer', 'min:0']
	    ]);

		$product = $product??new Product();
		$product->fill($request->all());
		
		// Set availability based on inventory count
		$product->is_available = $request->inventory_count > 0;
		
		$product->save();

		return redirect()->route('products_list');
	}

	public function delete(Request $request, Product $product) {
		// Check if user is authorized (Admin or Employee)
		abort_unless(auth()->user() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee')), 403);

		$product->delete();

		return redirect()->route('products_list');
	}
} 