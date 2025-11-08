{{-- resources/views/admin/verifikasi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Verifikasi Organisasi Kesenian')
@section('page-title', 'Verifikasi Organisasi')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Organisasi Menunggu Verifikasi</h5>
                    <div class="d-flex">
                        <form method="GET" action="{{ route('admin.verifikasi.index') }}" class="me-2">
                            <div class="input-group">
                                <input type="text" class="form-control" name="q" placeholder="Cari organisasi..."
                                    value="{{ request('q') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>Nama Organisasi</th>
                                <th>Jenis Kesenian</th>
                                <th>Ketua</th>
                                <th>Kecamatan</th>
                                <th>Tanggal Daftar</th>
                                <th>Jumlah Anggota</th>
                                <th width="200" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($organisasi as $index => $item)
                                <tr>
                                    <td class="text-center">
                                        {{ ($organisasi->currentPage() - 1) * $organisasi->perPage() + $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        @if ($item->nomor_induk)
                                            <br><small class="text-muted">No. Induk: {{ $item->nomor_induk }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->nama_jenis_kesenian ?? '-' }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->nama_ketua ?? '-' }}</strong>
                                            @if ($item->no_telp_ketua)
                                                <br><small class="text-muted">{{ $item->no_telp_ketua }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $item->nama_kecamatan ?? '-' }}</td>
                                    <td>{{ $item->tanggal_daftar ? $item->tanggal_daftar->format('d/m/Y') : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $item->anggota->count() }} Anggota</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.verifikasi.show', $item->id) }}" class="btn btn-info"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.verifikasi.approve', $item->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Setujui"
                                                    onclick="return confirm('Setujui organisasi ini?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.verifikasi.reject', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" title="Tolak"
                                                    onclick="return confirm('Tolak organisasi ini?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-check-circle fa-2x mb-3"></i>
                                            <br>
                                            Tidak ada organisasi yang menunggu verifikasi
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $organisasi->links() }}
            </div>
        </div>
    </div>
@endsection     
