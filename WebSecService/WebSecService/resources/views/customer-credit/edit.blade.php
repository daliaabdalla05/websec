
@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Edit Credit for {{ $customer->name }}</h2>
    
    <form method="POST" action="{{ route('customer_credit.update', $customer) }}">
    @csrf
    @method('PUT')
    
    <div class="form-group mb-3">
        <label for="credit">Credit Amount</label>
        <input type="number" step="0.01" class="form-control" 
               name="credit" value="{{ old('credit', $customer->credit) }}" required>
        @error('credit')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Update Credit</button>
</form>
</div>
@endsection