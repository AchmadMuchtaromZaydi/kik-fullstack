{{-- resources/views/admin/anggota/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota Kesenian')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Anggota Kesenian</h5>
                    <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Anggota
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Organisasi</th>
                                <th>WhatsApp</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggota as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nik ?? '-' }}</td>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $item->organisasi->nama ?? '-' }}</td>
                                    <td>{{ $item->whatsapp ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.anggota.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus data?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data anggota</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
