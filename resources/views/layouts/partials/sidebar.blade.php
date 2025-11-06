@auth
<div class="offcanvas offcanvas-lg offcanvas-start sidebar p-0" tabindex="-1" id="sidebarMenu"
     aria-labelledby="sidebarMenuLabel">

    <div class="offcanvas-header d-lg-none sidebar-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">
            <i class="fas fa-theater-masks me-2"></i>KIK System
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        <nav class="nav flex-column p-3">

            {{-- =======================
                 ADMIN MENU
            ======================= --}}
            @if(Auth::user()->role === 'admin')
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

                <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}"
                   href="{{ route('admin.users') }}">
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
            @endif

            {{-- =======================
                 USER-KIK MENU
            ======================= --}}
            @if(Auth::user()->role === 'user-kik')
                <a class="nav-link {{ Request::is('user-kik/dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>

                <a class="nav-link {{ Request::is('user-kik/organisasi*') ? 'active' : '' }}"
                   href="{{ route('user.organisasi.index') }}">
                    <i class="fas fa-building me-2"></i>Data Organisasi
                </a>

                 <a class="nav-link {{ Request::is('user-kik/anggota*') ? 'active' : '' }}"
                   href="{{ route('user.anggota.index') }}">
                    <i class="fas fa-users me-2"></i>Data Anggota
                </a>

                <a class="nav-link {{ Request::is('user-kik/inventaris*') ? 'active' : '' }}"
                   href="{{ route('user.inventaris.index') }}">
                    <i class="fas fa-boxes me-2"></i>Inventaris Barang
                </a>

                 <a class="nav-link {{ Request::is('user-kik/pendukung*') ? 'active' : '' }}"
                   href="{{ route('user.pendukung.index') }}">
                    <i class="fas fa-folder-open me-2"></i>Data Pendukung
                </a>

               {{-- Tambahan: Menu Verifikasi --}}
                <a class="nav-link {{ Request::is('user-kik/validasi*') ? 'active' : '' }}"
                href="{{ route('user.validasi.index') }}">
                    <i class="fas fa-check-circle me-2"></i>Verifikasi Data
                </a>
            @endif
        </nav>
    </div>
</div>
@endauth
