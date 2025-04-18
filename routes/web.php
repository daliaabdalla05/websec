// Product routes
Route::get('/products', [ProductsController::class, 'list'])->name('products_list');
Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');
Route::post('/products/{product}/comments', [ProductsController::class, 'addComment'])->name('products.add_comment');
Route::post('/comments/{comment}/approve', [ProductsController::class, 'approveComment'])->name('products.approve_comment');
Route::post('/comments/{comment}/reject', [ProductsController::class, 'rejectComment'])->name('products.reject_comment');
Route::delete('/comments/{comment}', [ProductsController::class, 'deleteComment'])->name('products.delete_comment'); 