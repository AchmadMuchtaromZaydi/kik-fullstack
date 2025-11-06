@extends('layouts.app')

@section('title', 'Data Organisasi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary"><i class="fas fa-building me-2"></i>Data Organisasi</h4>
        {{-- Tombol tambah hanya muncul jika belum ada organisasi --}}
        @if($organisasi->isEmpty())
            <a href="{{ route('user.organisasi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Organisasi
            </a>
        @endif
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($organisasi->isEmpty())
                <div class="alert alert-info mb-0">Belum ada data organisasi yang ditambahkan.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Nama Organisasi</th>
                                <th>Jenis Kesenian</th>
                                <th>Sub Kesenian</th>
                                <th>Alamat</th>
                                <th>Jumlah Anggota</th>
                                <th>Tanggal Berdiri</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organisasi as $i => $org)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $org->nama }}</td>
                                    <td>{{ $org->jenisKesenianObj->nama ?? '-' }}</td>
                                    <td>{{ $org->subKesenianObj->nama ?? '-' }}</td>
                                    <td>
                                        @php
                                            $alamat = $org->alamat_lengkap ?? '';
                                            $desa = $org->desaObj->nama ?? '';
                                            $kecamatan = $org->kecamatanObj->nama ?? '';
                                            $kabupaten = $org->kabupatenObj->nama ?? '';
                                        @endphp
                                        <small class="text-muted">
                                            {{ $alamat ? $alamat . ', ' : '' }}
                                            {{ $desa ? 'Desa ' . $desa . ', ' : '' }}
                                            {{ $kecamatan ? 'Kec. ' . $kecamatan . ', ' : '' }}
                                            {{ $kabupaten ? $kabupaten : '' }}
                                        </small>
                                    </td>
                                    <td>{{ $org->jumlah_anggota }}</td>
                                    <td>{{ \Carbon\Carbon::parse($org->tanggal_berdiri)->format('d-m-Y') }}</td>
                                    <td>
                                        <span class="badge
                                            @if($org->status == 'Request') bg-warning
                                            @elseif($org->status == 'Diterima') bg-success
                                            @elseif($org->status == 'Ditolak') bg-danger
                                            @else bg-secondary @endif">
                                            {{ $org->status }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Tombol aksi
                                        <div class="btn-group">
                                            <a href="{{ route('user.organisasi.edit', $org->id) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                               <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('user.organisasi.destroy', $org->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
