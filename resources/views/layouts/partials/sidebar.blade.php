<div class="bg-dark text-white p-3" id="sidebar-wrapper" style="min-width: 230px;">
    <div class="sidebar-heading fw-bold mb-4">SeniCards Admin</div>
    <div class="list-group list-group-flush">
        <a href="{{ route('admin.dashboard') }}"
            class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
        <a href="{{ url('/admin/anggota') }}"
            class="list-group-item list-group-item-action bg-dark text-white">Anggota</a>
        <a href="{{ url('/admin/organisasi') }}"
            class="list-group-item list-group-item-action bg-dark text-white">Organisasi</a>
        <a href="{{ url('/admin/kesenian') }}"
            class="list-group-item list-group-item-action bg-dark text-white">Kesenian</a>
        <a href="{{ url('/admin/validasi') }}"
            class="list-group-item list-group-item-action bg-dark text-white">Validasi</a>
        <a href="{{ url('/admin/users') }}"
            class="list-group-item list-group-item-action bg-dark text-white">Pengguna</a>
    </div>
</div>
