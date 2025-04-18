@extends('layouts.master')
@section('title', $product->name)
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="card-title">{{ $product->name }}</h1>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="card-text"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                    <p class="card-text"><strong>Inventory:</strong> {{ $product->inventory_count }}</p>
                    @if($product->is_available)
                        <span class="badge bg-success">Available</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
            </div>

            @if(auth()->user()->hasRole('Employee') && $pendingComments->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h3>Pending Comments</h3>
                </div>
                <div class="card-body">
                    @foreach($pendingComments as $comment)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->name }}</h6>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="card-text">{{ $comment->content }}</p>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('products.approve_comment', $comment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $comment->id }}">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $comment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Comment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('products.reject_comment', $comment->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="rejection_reason">Reason for rejection:</label>
                                                <textarea name="rejection_reason" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Comment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3>Approved Comments</h3>
                </div>
                <div class="card-body">
                    @auth
                    <form action="{{ route('products.add_comment', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="3" placeholder="Write a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                    </form>
                    @endauth

                    @foreach($product->comments as $comment)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $comment->user->name }}</h6>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="card-text">{{ $comment->content }}</p>
                                @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('Admin'))
                                    <form action="{{ route('products.delete_comment', $comment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 