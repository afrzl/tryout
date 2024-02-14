<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('img/logo.png') }}" alt="UKM Bimbel" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->profile_photo_url }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ (request()->segment(2) == 'dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @role('admin')
                <li class="nav-header">Data Pengguna</li>
                <li class="nav-item ">
                    <a href="{{ route('user.index') }}" class="nav-link {{ (request()->segment(2) == 'user') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('admin.admin.index') }}" class="nav-link {{ (request()->segment(2) == 'admin') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-gear"></i>
                        <p>
                            Admin
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('admin.himada.index') }}" class="nav-link {{ (request()->segment(2) == 'himada') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-viewfinder"></i>
                        <p>
                            Himada
                        </p>
                    </a>
                </li>
                <li class="nav-header">Data Ujian</li>
                <li class="nav-item">
                    <a href="{{ route('admin.paket.index') }}" class="nav-link {{ (request()->segment(2) == 'paket') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                            Paket Ujian
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.ujian.index') }}" class="nav-link {{ (request()->segment(2) == 'ujian') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Ujian
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.voucher.index') }}" class="nav-link {{ (request()->segment(2) == 'voucher') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-ticket"></i>
                        <p>
                            Voucher
                        </p>
                    </a>
                </li>
                <li class="nav-header">Data Peserta Ujian</li>
                <li class="nav-item">
                    <a href="{{ route('admin.peserta_ujian.index') }}" class="nav-link {{ (request()->segment(2) == 'peserta_ujian') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-pen"></i>
                        <p>
                            Peserta Ujian
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pembelian.index') }}" class="nav-link {{ (request()->segment(2) == 'pembelian') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>
                            Pembelian Paket
                        </p>
                    </a>
                </li>

                @endrole
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
