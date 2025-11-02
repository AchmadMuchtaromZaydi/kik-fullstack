{{-- resources/views/layouts/partials/header.blade.php --}}
<header class="bg-white shadow-sm py-3 fixed-top border-bottom">
    <div class="container-fluid d-flex align-items-center">

        {{-- Tombol Pemicu Sidebar Mobile --}}
        @auth
            <button class="btn btn-outline-primary me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="fas fa-bars"></i>
            </button>
        @endauth

        {{-- JUDUL DESKTOP --}}
        <a href="{{ auth()->check() ? route('admin.dashboard') : route('home') }}"
            class="text-decoration-none text-primary d-none d-lg-block me-auto">
            <h5 class="mb-0">
                <i class="fas fa-theater-masks me-2"></i>
                <strong>KIK System</strong>
                @auth
                    <small class="text-muted">Admin Panel</small>
                @else
                    <small class="text-muted">Sistem Informasi Kesenian</small>
                @endauth
            </h5>
        </a>

        {{-- JUDUL MOBILE --}}
        <h4 class="mb-0 text-primary me-auto d-lg-none">
            @yield('page-title', auth()->check() ? 'Dashboard' : 'Home')
        </h4>

        {{-- Dropdown User hanya tampil jika login --}}
        @auth
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
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
                <a href="{{ route('auth.login') }}" class="btn btn-outline-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="{{ route('auth.register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            </div>
        @endauth
    </div>
</header>
