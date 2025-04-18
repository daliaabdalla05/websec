@extends('layouts.master')
@section('title', 'Products')
@section('content')

<form action="{{route('products_save', $product->id)}}" method="post">
    @csrf
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">
        <strong>Error!</strong> {{$error}}
    </div>
    @endforeach
    
    <div class="row mb-2">
        <div class="col-6">
            <label for="code" class="form-label">Code:</label>
            <input type="text" class="form-control" placeholder="Code" name="code" required value="{{$product->code}}">
        </div>
        <div class="col-6">
            <label for="model" class="form-label">Model:</label>
            <input type="text" class="form-control" placeholder="Model" name="model" required value="{{$product->model}}">
        </div>
    </div>
    
    <div class="row mb-2">
        <div class="col">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" placeholder="Name" name="name" required value="{{$product->name}}">
        </div>
    </div>
    
    <div class="row mb-2">
        <div class="col-4">
            <label for="price" class="form-label">Price:</label>
            <input type="number" step="0.01" class="form-control" placeholder="Price" name="price" required value="{{$product->price}}">
        </div>
        <div class="col-4">
            <label for="inventory_count" class="form-label">Inventory:</label>
            <input type="number" class="form-control" placeholder="Quantity" name="inventory_count" min="0" required value="{{$product->inventory_count ?? 0}}">
        </div>
        <div class="col-4">
            <label for="photo" class="form-label">Photo:</label>
            <input type="text" class="form-control" placeholder="Photo URL" name="photo" value="{{$product->photo}}">
            @if($product->photo)
                <img src="{{ asset($product->photo) }}" width="50" class="mt-2">
            @endif
        </div>
    </div>
    
    <div class="row mb-2">
        <div class="col">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" placeholder="Description" name="description" required>{{$product->description}}</textarea>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" {{ $product->is_available ? 'checked' : '' }}>
                <label class="form-check-label" for="is_available">
                    Product is available for sale
                </label>
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Save Product</button>
    <a href="{{ route('products_list') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection