<nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
    <div class="container-fluid pe-0">
        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="/">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
                @hasrole('admin')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ route('dashboard.admin') }}">
                        <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                        Dashboard
                    </a>
                </li>
                @endhasrole
                @auth
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ route('profile.show') }}">
                        <i class="fa fa-user opacity-6 text-dark me-1"></i>
                        {{ auth()->user()->name }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center me-2 active" href="#" aria-current="page" onclick="document.getElementById('logoutForm').submit()">
                        <i class="fa fa-sign-out opacity-6 text-dark me-1"></i>
                        Logout
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route('register') }}">
                        <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                        Daftar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-2" href="{{ route('login') }}">
                        <i class="fas fa-key opacity-6 text-dark me-1"></i>
                        Login
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<form action="{{ route('logout') }}" id="logoutForm" method="post" style="display: none;">
    @csrf
</form>
