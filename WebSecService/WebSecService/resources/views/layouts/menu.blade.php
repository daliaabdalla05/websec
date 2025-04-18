<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./">Home</a>
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
                <a class="nav-link" href="{{route('products_list')}}">Products</a>
            </li>
            @auth
            @can('show_users')
            <li class="nav-item">
                <a class="nav-link" href="{{route('users')}}">Users</a>
            </li>
            @endcan
        
        @endauth


            
            @can('edit_customer_credit')
            <li class="nav-item">
                <a class="nav-link" href="{{route('customer_credit')}}">Customer Credit</a>
            </li>
            @endcan
            @role('Admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('employees.create') }}">
                    <i class="fas fa-user-plus"></i> Add Employee
                </a>
            </li>
            @endrole
        </ul>
        <ul class="navbar-nav">
            @auth
            <li class="nav-item">
                <a class="nav-link" href="{{route('profile')}}">{{auth()->user()->name}}</a>
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
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.bought') }}">
                        <i class="fas fa-shopping-bag me-1"></i> My Purchases
                    </a>
                </li>
                @endauth
        </ul>
    </div>
</nav>