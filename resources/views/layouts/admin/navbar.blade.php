<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ auth()->user()->profile_photo_url }}" class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                    <li class="user-header bg-gray-dark">
                        <img src="{{ auth()->user()->profile_photo_url }}" class="img-circle elevation-2" alt="User Image">
                        <p>
                            {{ auth()->user()->name }}
                            <small> {{ auth()->user()->roles()->pluck('name')[0] }}</small>
                        </p>
                    </li>

                    <li class="user-footer">
                        <a href="{{ route('profile.show') }}" class="btn btn-default">Profile</a>
                        <a class="btn btn-default float-right" onclick="document.getElementById('logoutForm').submit()">Log out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </ul>
</nav>

<form action="{{ route('logout') }}" id="logoutForm" method="post" style="display: none;">
    @csrf
</form>
