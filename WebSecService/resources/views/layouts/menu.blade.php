<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./even">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./prime">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./multable">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('products_list')}}">Store</a>
            </li>
            
            @auth
                @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Employee'))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('users')}}">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('customer_credit')}}">Customer Credit</a>
                </li>
                @endif
                
                @if(auth()->user()->hasRole('Admin'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('employees.create') }}">
                        <i class="fas fa-user-plus"></i> Add Employee
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->hasRole('Customer'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.bought') }}">
                        <i class="fas fa-shopping-bag me-1"></i> My Purchases
                    </a>
                </li>
                @endif
            @endauth
        </ul>
        
        <ul class="navbar-nav">
            @auth
            <li class="nav-item">
                <a class="nav-link" href="{{route('profile')}}">
                    {{auth()->user()->name}}
                    @if(auth()->user()->hasRole('Admin'))
                        <span class="badge bg-danger">Admin</span>
                    @elseif(auth()->user()->hasRole('Employee'))
                        <span class="badge bg-warning text-dark">Employee</span>
                    @elseif(auth()->user()->hasRole('Customer'))
                        <span class="badge bg-info text-dark">Customer</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('do_logout')}}">Logout</a>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{route('login')}}">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('register')}}">Register</a>
            </li>
            @endauth
        </ul>
    </div>
</nav>