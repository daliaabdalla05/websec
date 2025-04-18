<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Comment;
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

		return view('products.list', compact('products'));
	}

	public function show(Product $product)
	{
		$product->load(['comments.user' => function($query) {
			$query->where('status', 'approved');
		}]);
		
		$pendingComments = null;
		if (auth()->user()->hasRole('Employee')) {
			$pendingComments = $product->comments()->pending()->with('user')->get();
		}

		return view('products.show', compact('product', 'pendingComments'));
	}

	public function addComment(Request $request, Product $product)
	{
		$this->validate($request, [
			'content' => 'required|string|max:1000'
		]);

		$comment = new Comment([
			'content' => $request->content,
			'user_id' => auth()->id(),
			'status' => 'pending'
		]);

		$product->comments()->save($comment);

		return back()->with('success', 'Comment submitted for approval!');
	}

	public function approveComment(Comment $comment)
	{
		if (!auth()->user()->hasRole('Employee')) {
			abort(403);
		}

		$comment->update(['status' => 'approved']);

		return back()->with('success', 'Comment approved successfully!');
	}

	public function rejectComment(Request $request, Comment $comment)
	{
		if (!auth()->user()->hasRole('Employee')) {
			abort(403);
		}

		$this->validate($request, [
			'rejection_reason' => 'required|string|max:500'
		]);

		$comment->update([
			'status' => 'rejected',
			'rejection_reason' => $request->rejection_reason
		]);

		return back()->with('success', 'Comment rejected successfully!');
	}

	public function deleteComment(Comment $comment)
	{
		if (auth()->id() !== $comment->user_id && !auth()->user()->hasRole('Admin')) {
			abort(403);
		}

		$comment->delete();

		return back()->with('success', 'Comment deleted successfully!');
	}

	// ... existing code ...
} 