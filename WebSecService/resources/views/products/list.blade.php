@extends('layouts.master')
@section('title', 'Products')
@section('content')
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

<div class="row mt-2">
    <div class="col col-10">
        <h1>Products</h1>
    </div>
    <div class="col col-2">
        @if(auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee')))
        <a href="{{route('products_edit')}}" class="btn btn-success form-control">Add Product</a>
        @endif
    </div>
</div>

<!-- Search Form -->
<form>
    <div class="row">
        <div class="col col-sm-2">
            <select name="inventory" class="form-select">
                <option value="" {{ request()->inventory==""?"selected":"" }}>All Inventory Status</option>
                <option value="1" {{ request()->inventory=="1"?"selected":"" }}>In Inventory</option>
                <option value="0" {{ request()->inventory=="0"?"selected":"" }}>Out of Inventory</option>
            </select>
        </div>
    </div>
</form>

@if(session('success'))
    <div class="alert alert-success mt-2">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-2">
        {{ session('error') }}
    </div>
@endif

@if($products->isEmpty())
    <div class="alert alert-info mt-4">
        No products available. Please add some products.
    </div>
@else
    @foreach($products as $product)
        <div class="card mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col col-sm-12 col-lg-4">
                        @if($product->photo)
                            <img src="{{asset("images/$product->photo")}}" class="img-thumbnail" alt="{{$product->name}}" width="100%">
                        @else
                            <div class="bg-light text-center p-5">
                                <span class="text-muted">No Image Available</span>
                            </div>
                        @endif
                    </div>
                    <div class="col col-sm-12 col-lg-8 mt-3">
                        <div class="row mb-2">
                            <div class="col-8">
                                <h3>{{$product->name}}</h3>
                                <!-- Inventory Status Badge -->
                                @if($product->inventory_count > 0 && $product->is_available)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> In Stock ({{ $product->inventory_count }} available)
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times"></i> Out of Stock
                                    </span>
                                @endif
                            </div>
                            <div class="col col-2">
                                @if(auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee')))
                                <a href="{{route('products_edit', $product->id)}}" class="btn btn-success form-control">Edit</a>
                                @endif
                            </div>
                            <div class="col col-2">
                                @if(auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee')))
                                <a href="{{route('products_delete', $product->id)}}" class="btn btn-danger form-control">Delete</a>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Buy Button with Inventory Check -->
                        <div class="col col-2 mb-3">
                            @if(auth()->check() && auth()->user()->hasRole('Customer'))
                            <form action="{{ route('products.purchase', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary form-control" 
                                    {{ (!$product->is_available || $product->inventory_count < 1) ? 'disabled' : '' }}>
                                    Buy
                                </button>
                                @if(!$product->is_available || $product->inventory_count < 1)
                                    <small class="text-danger">Out of Stock</small>
                                @endif
                            </form>
                            @endif
                        </div>

                        <table class="table table-striped">
                            <tr><th width="20%">Name</th><td>{{$product->name}}</td></tr>
                            <tr><th>Model</th><td>{{$product->model}}</td></tr>
                            <tr><th>Code</th><td>{{$product->code}}</td></tr>
                            <tr><th>Price</th><td>${{ number_format($product->price, 2) }}</td></tr>
                            <tr>
                                <th>Inventory</th>
                                <td>{{ $product->inventory_count }}</td>
                            </tr>
                            <tr><th>Description</th><td>{{$product->description}}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
@endsection