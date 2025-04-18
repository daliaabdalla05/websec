<!-- resources/views/products/purchases.blade.php -->
@extends('layouts.master')
@section('title', 'Purchase History')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <div class="card bg-light border-info">
                <div class="card-body">
                    <h5 class="card-title mb-0">Store Credit</h5>
                    <p class="card-text fs-4 text-success">
                        ${{ number_format(Auth::user()->credit ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <h2>Your Purchase History</h2>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if($purchases->isEmpty())
        <div class="alert alert-info">You haven't made any purchases yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price at Purchase</th>
                        <th>Purchase Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $product)
                    <tr>
                        <td>
                            @if($product->photo)
                                <img src="{{ asset('images/'.$product->photo) }}" width="50" class="mr-2">
                            @endif
                            {{ $product->name }}
                        </td>
                        <td>${{ number_format($product->pivot->price_at_purchase, 2) }}</td>
                        <td>{{ $product->pivot->bought_at }}</td>
                        <td>
                            <span class="badge bg-{{ $product->pivot->status == 'completed' ? 'success' : 'warning' }}">
                                {{ $product->pivot->status }}
                            </span>
                        </td>
                        <td>
                            @if($product->pivot->status == 'completed')
                                @php
                                    $purchaseTime = new DateTime($product->pivot->bought_at);
                                    $now = new DateTime();
                                    $diff = $purchaseTime->diff($now);
                                    $canRefund = $diff->days <= 1;
                                @endphp
                                
                                @if($canRefund)
                                <form action="{{ route('purchases.refund', $product->pivot->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" 
                                            onclick="return confirm('Are you sure you want to refund this purchase?')">
                                        Refund
                                    </button>
                                </form>
                                @else
                                <span class="text-muted">Refund period expired</span>
                                @endif
                            @elseif($product->pivot->status == 'refunded')
                                <span class="text-success">Refunded</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route('products_list') }}" class="btn btn-primary">Return to Store</a>
        </div>
    @endif
</div>
@endsection