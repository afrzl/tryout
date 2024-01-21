<nav id="navbar" class="navbar">
    <ul>
        <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
        <li><a class="nav-link scrollto" href="#about">About</a></li>
        <li><a class="nav-link scrollto" href="#features">Features</a></li>
        <li><a class="nav-link scrollto" href="#gallery">Gallery</a></li>
        <li><a class="nav-link scrollto" href="#team">Team</a></li>
        <li><a class="nav-link scrollto" href="#pricing">Pricing</a></li>
        <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
                <li><a href="#">Drop Down 1</a></li>
                <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
                    <ul>
                        <li><a href="#">Deep Drop Down 1</a></li>
                        <li><a href="#">Deep Drop Down 2</a></li>
                        <li><a href="#">Deep Drop Down 3</a></li>
                        <li><a href="#">Deep Drop Down 4</a></li>
                        <li><a href="#">Deep Drop Down 5</a></li>
                    </ul>
                </li>
                <li><a href="#">Drop Down 2</a></li>
                <li><a href="#">Drop Down 3</a></li>
                <li><a href="#">Drop Down 4</a></li>
            </ul>
        </li>
        <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
        @hasrole('admin')
            <li><a class="nav-link scrollto" href="{{ route('dashboard.admin') }}">Admin</a></li>
        @endhasrole

        @auth
            <li class="dropdown"><a href="#"><span>{{ auth()->user()->name }}</span> <i class="bi bi-chevron-down"></i></a>
                <ul>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#" onclick="document.getElementById('logoutForm').submit()">Logout</a></li>
                </ul>
            </li>
        @else
            <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
        @endauth
    </ul>
    <i class="bi bi-list mobile-nav-toggle"></i>
</nav><!-- .navbar -->

<form action="{{ route('logout') }}" id="logoutForm" method="post" style="display: none;">
    @csrf
</form>
