<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase; 


class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");
		$query->when($request->inventory !== null, function($q) use ($request) {
			return $q->where('inventory', '>', 0);
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

		return view('products.list', compact('products'));
	}

public function boughtProducts()
{
    // Eager load the products with their details
    $purchases = auth()->user()->boughtProducts()

                  ->paginate(10);
                  
    return view('products.bought-list', compact('purchases'));
}
	public function purchase(Product $product)
	{
		$user = auth()->user();
		
		if ($user->credit < $product->price) {
			return redirect()->route('insufficient.credit')
				   ->with('product', $product);
		}

	
		DB::transaction(function () use ($user, $product) {
			$user->decrement('credit', $product->price);
			
			Purchase::create([
				'user_id' => $user->id,
				'product_id' => $product->id,
				'price' => $product->price,
				'remaining_credit' => $user->credit - $product->price
			]);
		});
	
		return back()->with('success', 'Product purchased successfully!');
	}


	public function edit(Request $request, Product $product = null) {

		if(!auth()->user()) return redirect('/');

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {

		$this->validate($request, [
	        'code' => ['required', 'string', 'max:32'],
	        'name' => ['required', 'string', 'max:128'],
	        'model' => ['required', 'string', 'max:256'],
	        'description' => ['required', 'string', 'max:1024'],
	        'price' => ['required', 'numeric'],
			'inventory' => ['required', 'integer', 'min:0']
	    ]);

		$product = $product??new Product();
		$product->fill($request->all());
		$product->save();

		return redirect()->route('products_list');
	}

	public function delete(Request $request, Product $product) {

		if(!auth()->user()->hasPermissionTo('delete_products')) abort(401);

		$product->delete();

		return redirect()->route('products_list');
	}
} 