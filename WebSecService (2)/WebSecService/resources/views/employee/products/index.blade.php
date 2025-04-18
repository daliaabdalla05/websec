
@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h2>Product Management</h2>
        <a href="{{ route('employee.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Inventory</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->inventory_count }}</td>
                    <td>
                        <span class="badge {{ $product->is_available ? 'bg-success' : 'bg-secondary' }}">
                            {{ $product->is_available ? 'Available' : 'Out of Stock' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('employee.products.edit', $product->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                           <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('employee.products.destroy', $product->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection