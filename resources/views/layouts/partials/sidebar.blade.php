{{-- resources/views/layouts/partials/sidebar.blade.php --}}
@auth
    <div class="offcanvas offcanvas-lg offcanvas-start sidebar p-0" tabindex="-1" id="sidebarMenu"
        aria-labelledby="sidebarMenuLabel">

        {{-- Header Mobile --}}
        <div class="offcanvas-header d-lg-none sidebar-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">
                <i class="fas fa-theater-masks me-2"></i>KIK System
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                aria-label="Close"></button>
        </div>

        {{-- Konten Sidebar --}}
        <div class="offcanvas-body p-0">
            <nav class="nav flex-column p-3">
                <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>

                <a class="nav-link {{ Request::is('admin/kesenian*') ? 'active' : '' }}"
                    href="{{ route('admin.kesenian.index') }}">
                    <i class="fas fa-music me-2"></i>Data Kesenian
                </a>

                <a class="nav-link {{ Request::is('admin/jenis-kesenian*') ? 'active' : '' }}"
                    href="{{ route('admin.jenis-kesenian') }}">
                    <i class="fas fa-list me-2"></i>Jenis Kesenian
                </a>

                <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                    <i class="fas fa-users me-2"></i>Kelola User
                </a>


                <a class="nav-link {{ Request::is('admin/anggota*') ? 'active' : '' }}"
                    href="{{ route('admin.anggota.index') }}">
                    <i class="fas fa-user-friends me-2"></i>Anggota Kesenian
                </a>


                <a class="nav-link {{ Request::is('admin/laporan*') ? 'active' : '' }}"
                    href="{{ route('admin.laporan') }}">
                    <i class="fas fa-chart-bar me-2"></i>Laporan
                </a>
            </nav>
        </div>
    </div>
@endauth
