<!-- resources/views/products/purchases.blade.php -->
@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Your Purchase History</h2>
    
    @if($purchases->isEmpty())
        <div class="alert alert-info">You haven't made any purchases yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Purchase Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $product)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/'.$product->image) }}" width="50" class="mr-2">
                            {{ $product->name }}
                            @if($product->category)
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            @endif
                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->pivot->bought_at->format('M j, Y g:i a') }}</td>
                        <td>
                            <span class="badge bg-success">Completed</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $purchases->links() }}
            </div>
        </div>
    @endif
</div>
@endsection