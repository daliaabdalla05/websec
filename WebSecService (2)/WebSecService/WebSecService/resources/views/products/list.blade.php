@extends('layouts.master')
@section('title', 'Test Page')
@section('content')
<div class="row mb-3">
    <div class="col">
        <div class="card bg-light border-info">
            <div class="card-body">
                <h5 class="card-title mb-0">Store Credit</h5>
                <p class="card-text fs-4 text-success">
                    ₱{{ number_format(Auth::user()->credit, 2) }}
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
        @can('add_products')
        <a href="{{route('products_edit')}}" class="btn btn-success form-control">Add Product</a>
        @endcan
    </div>
</div>

<!-- Search Form -->
<form>
    <div class="row">
        <!-- Existing search fields... -->
        <div class="col col-sm-2">
            <select name="in_Inventory" class="form-select">
                <option value="" {{ request()->in_Inventory==""?"selected":"" }}>All Inventory Status</option>
                <option value="1" {{ request()->in_Inventory=="1"?"selected":"" }}>In Inventory</option>
                <option value="0" {{ request()->in_Inventory=="0"?"selected":"" }}>Out of Inventory</option>
            </select>
        </div>
        <!-- Rest of your search fields... -->
    </div>
</form>

@foreach($products as $product)
    <div class="card mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col col-sm-12 col-lg-4">
                    <img src="{{asset("images/$product->photo")}}" class="img-thumbnail" alt="{{$product->name}}" width="100%">
                </div>
                <div class="col col-sm-12 col-lg-8 mt-3">
                    <div class="row mb-2">
                        <div class="col-8">
                            <h3>{{$product->name}}</h3>
                            <!-- Inventory Status Badge -->
                            @if($product->inventory > 0)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> In Inventory ({{ $product->Inventory }} available)
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times"></i> Out of Inventory
                                </span>
                            @endif
                        </div>
                        <div class="col col-2">
                            @can('edit_products')
                            <a href="{{route('products_edit', $product->id)}}" class="btn btn-success form-control">Edit</a>
                            @endcan
                        </div>
                        <div class="col col-2">
                            @can('delete_products')
                            <a href="{{route('products_delete', $product->id)}}" class="btn btn-danger form-control">Delete</a>
                            @endcan
                        </div>
                    </div>
                    
                    <!-- Buy Button with Inventory Check -->
                    <div class="col col-2">
                        <form action="{{ route('products.purchase', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary form-control" 
                                {{ $product->inventory < 1 ? 'disabled' : '' }}>
                                Buy
                            </button>
                            @if($product->inventory < 1)
                                <small class="text-danger">Out of Inventory</small>
                            @endif
                        </form>
                    </div>

                    <table class="table table-striped">
                        <tr><th width="20%">Name</th><td>{{$product->name}}</td></tr>
                        <tr><th>Model</th><td>{{$product->model}}</td></tr>
                        <tr><th>Code</th><td>{{$product->code}}</td></tr>
                        <tr><th>Price</th><td>₱{{ number_format($product->price, 2) }}</td></tr>
                        <tr><th>Inventory</th><td>{{ $product->Inventory }}</td></tr>
                        <tr><th>Description</th><td>{{$product->description}}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection