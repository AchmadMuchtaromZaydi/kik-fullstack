{{-- resources/views/layouts/partials/header.blade.php --}}
<header class="admin-header shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center h-100">

        {{-- Tombol Pemicu Sidebar Mobile --}}
        @auth
            <button class="btn btn-outline-primary me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="fas fa-bars"></i>
            </button>
        @endauth

        {{-- JUDUL DESKTOP --}}
        <a href="{{ auth()->check() ? route('admin.dashboard') : route('home') }}"
            class="text-decoration-none d-none d-lg-block me-auto admin-title">
            <h5 class="mb-0">
                <i class="fas fa-theater-masks me-2"></i>
                <strong>KIK System</strong>
                @auth
                    <small>Admin Panel</small>
                @else
                    <small>Sistem Informasi Kesenian</small>
                @endauth
            </h5>
        </a>

        {{-- JUDUL MOBILE --}}
        <h4 class="mb-0 text-white me-auto d-lg-none">
            @yield('page-title', auth()->check() ? 'Dashboard' : 'Home')
        </h4>

        {{-- Dropdown User hanya tampil jika login --}}
        @auth
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            {{-- Tombol Login/Register untuk guest --}}
            <div class="d-flex gap-2">
                <a href="{{ route('auth.login') }}" class="btn btn-outline-light">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="{{ route('auth.register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            </div>
        @endauth
    </div>
</header>
