<div class="navbar-menu h-100 d-flex flex-column">
    <!-- Logo/Brand -->
    <div class="navbar-brand-box text-center py-4">
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
            class="logo text-decoration-none">
            <div class="logo-lg">
                <img src="{{ asset('assets/img/logo-white.png') }}" alt="logo" class="img-fluid"
                    style="max-height: 60px;" />
            </div>
            <div class="logo-sm mt-2">
                <small class="text-white">Kartu Induk Kesenian</small>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <ul class="navbar-nav sidebar flex-grow-1">
        @if (Auth::user()->role === 'user-kik')
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endif

        @if (Auth::user()->role === 'admin')
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-list me-2"></i>
                    <span>Data Kesenian</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.jenis-kesenian') }}"
                    class="nav-link {{ request()->routeIs('admin.jenis-kesenian') ? 'active' : '' }}">
                    <i class="fas fa-th-list me-2"></i>
                    <span>Data Jenis Kesenian</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users') }}"
                    class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>
                    <span>Data Users</span>
                </a>
            </li>
        @endif
    </ul>

    <!-- Logout Section -->
    <div class="sidebar-footer p-3 border-top">
        <div class="d-flex justify-content-between align-items-center">
            <div class="user-info">
                <small class="text-white">{{ Auth::user()->name }}</small>
                <br>
                <small class="text-white-50">{{ Auth::user()->role }}</small>
            </div>
            <a href="#" class="nav-link text-white p-0"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
    @csrf
</form>
