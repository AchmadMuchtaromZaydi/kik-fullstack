@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Data Users</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Daftar Pengguna</h5>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah User
                            </a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>WhatsApp</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->whatsapp ?? '-' }}</td>
                                            {{-- Di dalam tabel --}}
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : 'success' }}">
                                                    {{ $user->role }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $user->isActive ? 'success' : 'danger' }}">
                                                    {{ $user->isActive ? 'Aktif' : 'Non-Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->role === 'admin')
                                                    <span class="badge bg-info">Auto Verified</span>
                                                @else
                                                    <span
                                                        class="badge bg-{{ $user->code_verified == 1 ? 'success' : 'warning' }}">
                                                        {{ $user->code_verified == 1 ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @if ($user->code_verified != 1)
                                                        <form action="{{ route('admin.users.reset-verification', $user) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-info"
                                                                title="Reset Verifikasi">
                                                                <i class="fas fa-sync"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
