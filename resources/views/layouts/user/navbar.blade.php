<nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
    <div class="container-fluid pe-0">
        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{ route('dashboard') }}">
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
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user opacity-6 text-dark me-1"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 ms-n4" aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="{{ route('profile.edit') }}">
                                <div class="d-flex py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold"><i class="fa fa-user opacity-6 text-dark me-1"></i> Profil
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" onclick="document.getElementById('logoutForm').submit()">
                                <div class="d-flex py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold"><i class="fa fa-sign-out opacity-6 text-dark me-1"></i> Logout
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
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
