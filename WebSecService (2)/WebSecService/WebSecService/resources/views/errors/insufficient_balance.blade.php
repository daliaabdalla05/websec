@extends('layouts.master')

@section('content')
<div class="container">
    <div class="alert alert-danger text-center">
        <h2>Insufficient Credit!</h2>
        <p>You don't have enough credit to complete this purchase.</p>
        <p>Current Credit: ₱{{ number_format(Auth::user()->credit, 2) }}</p>
        <a href="{{ route('products_list') }}" class="btn btn-primary">
            Return to Products
        </a>
    </div>
</div>
@endsection