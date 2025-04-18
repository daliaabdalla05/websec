@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Manage Customer Credit</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Current Credit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>${{ number_format($customer->credit, 2) }}</td>
                    <td>
                        <a href="{{ route('customer_credit.edit', $customer) }}" class="btn btn-sm btn-primary">
                            Edit Credit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection