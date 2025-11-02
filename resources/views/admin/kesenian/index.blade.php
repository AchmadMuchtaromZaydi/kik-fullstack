{{-- resources/views/admin/kesenian/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Kesenian')
@section('page-title', 'Data Kesenian')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Organisasi Kesenian</h5>
                    <a href="{{ route('admin.kesenian.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Form Pencarian dan Filter -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.kesenian.index') }}" class="row g-3">
                            <!-- Pencarian -->
                            <div class="col-md-6">
                                <label for="q" class="form-label">Pencarian</label>
                                <input type="text" class="form-control" id="q" name="q"
                                    placeholder="Cari berdasarkan Nama, Nomor Induk, Jenis Kesenian, Ketua, Alamat, Desa, Kecamatan, No. Telp..."
                                    value="{{ request('q') }}">
                            </div>

                            <!-- Filter Jenis Kesenian -->
                            <div class="col-md-3">
                                <label for="jenis_kesenian" class="form-label">Filter Jenis Kesenian</label>
                                <select class="form-select" id="jenis_kesenian" name="jenis_kesenian">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisKesenian as $jenis)
                                        <option value="{{ $jenis }}"
                                            {{ request('jenis_kesenian') == $jenis ? 'selected' : '' }}>
                                            {{ $jenis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Kecamatan -->
                            <div class="col-md-3">
                                <label for="kecamatan" class="form-label">Filter Kecamatan</label>
                                <select class="form-select" id="kecamatan" name="kecamatan">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach ($kecamatanList as $kecamatan)
                                        <option value="{{ $kecamatan }}"
                                            {{ request('kecamatan') == $kecamatan ? 'selected' : '' }}>
                                            {{ $kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Cari & Filter
                                </button>
                                <a href="{{ route('admin.kesenian.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh me-2"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Jumlah Data dan Urutan -->
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Menampilkan <strong>{{ $dataKesenian->count() }}</strong> data organisasi kesenian
                    @if ($hasSearch)
                        <span class="badge bg-warning ms-2">Mode Pencarian: Diurutkan berdasarkan terbaru</span>
                    @else
                        <span class="badge bg-success ms-2">Mode Normal</span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>Nama Kesenian</th>
                                <th>Nomor Induk</th>
                                <th>Jenis Kesenian</th>
                                <th>Alamat</th>
                                <th>Ketua</th>
                                <th>Tgl Daftar</th>
                                <th>Tgl Expired</th>
                                <th>Status</th>
                                <th width="120" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataKesenian as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td>
                                        @if ($item->nomor_induk)
                                            <span class="fw-bold text-primary">{{ $item->nomor_induk }}</span>
                                        @else
                                            <span class="text-muted">Belum ada</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->nama_jenis_kesenian ?? ($item->jenis_kesenian ?? '-') }}</td>
                                    <td>
                                        <div class="small">
                                            {{ $item->alamat ?? '-' }}
                                            @if ($item->desa || $item->kecamatan)
                                                <br>
                                                <span class="text-muted">
                                                    @if ($item->desa)
                                                        Desa {{ $item->desa }}
                                                    @endif
                                                    @if ($item->kecamatan)
                                                        @if ($item->desa)
                                                            ,
                                                        @endif
                                                        Kec. {{ $item->kecamatan }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item->nama_ketua ?? '-' }}</strong>
                                            @if ($item->no_telp_ketua)
                                                <br>
                                                <small class="text-muted">{{ $item->no_telp_ketua }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->tanggal_daftar)
                                            <span
                                                class="small">{{ \Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y') }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->tanggal_expired)
                                            @if (\Carbon\Carbon::parse($item->tanggal_expired)->isPast())
                                                <span class="badge bg-danger small">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y') }}
                                                </span>
                                            @elseif(\Carbon\Carbon::parse($item->tanggal_expired)->diffInDays(now()) <= 30)
                                                <span class="badge bg-warning text-dark small">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span
                                                    class="small">{{ \Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y') }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Request' => 'warning',
                                                'Allow' => 'success',
                                                'Denny' => 'danger',
                                                'DataLama' => 'info',
                                            ];
                                            $color = $statusColors[$item->status] ?? 'secondary';
                                            $statusTexts = [
                                                'Request' => 'Menunggu',
                                                'Allow' => 'Diterima',
                                                'Denny' => 'Ditolak',
                                                'DataLama' => 'Data Lama',
                                            ];
                                            $text = $statusTexts[$item->status] ?? $item->status;
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ $text }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.kesenian.edit', $item->id) }}" class="btn btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.kesenian.destroy', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Hapus data?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <br>
                                            Tidak ada data kesenian
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Info jika data banyak -->
                    @if ($dataKesenian->count() > 10)
                        <div class="alert alert-light mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-arrows-alt-v me-1"></i>
                                Total {{ $dataKesenian->count() }} data - Gunakan scroll untuk melihat semua data
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-responsive {
            max-height: 80vh;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #212529;
            z-index: 10;
        }

        .card-body {
            padding: 1.5rem;
        }
    </style>
@endsection
