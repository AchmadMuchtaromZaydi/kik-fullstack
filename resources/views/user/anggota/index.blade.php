@extends('layouts.app')
@section('title', 'Data Anggota')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data Anggota Organisasi</h5>

            @if($jumlahSaatIni < $jumlahMaks)
                <a href="{{ route('user.anggota.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah Anggota
                </a>
            @else
                <span class="badge bg-warning text-dark">Kuota anggota sudah penuh</span>
            @endif
        </div>

        <div class="card-body">
            {{-- Pesan notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @elseif(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            {{-- Informasi jumlah anggota --}}
            <div class="mb-3">
                <strong>Jumlah anggota maksimal:</strong> {{ $jumlahMaks }}<br>
                <strong>Sudah terdaftar:</strong> {{ $jumlahSaatIni }}
            </div>

            {{-- Cek struktur penting (Ketua & Sekretaris) --}}
            @php
                $punyaKetua = $anggota->where('jabatan', 'Ketua')->count() > 0;
                $punyaSekretaris = $anggota->where('jabatan', 'Sekretaris')->count() > 0;
            @endphp

            @if(!$punyaKetua || !$punyaSekretaris)
                <div class="alert alert-warning">
                    Struktur organisasi belum lengkap.
                    @if(!$punyaKetua) <strong>Ketua</strong> belum diisi. @endif
                    @if(!$punyaSekretaris) <strong>Sekretaris</strong> belum diisi. @endif
                </div>
            @endif

            {{-- Tabel data anggota --}}
            @if($anggota->isEmpty())
                <div class="alert alert-info">Belum ada anggota terdaftar.</div>
            @else
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Jabatan</th>
                            <th>Jenis Kelamin</th>
                            <th>Umur</th>
                            <th>Pekerjaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota as $index => $a)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $a->nama }}</td>
                            <td>{{ $a->nik }}</td>
                            <td>{{ $a->jabatan }}</td>
                            <td class="text-center">{{ $a->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="text-center">
                                @if ($a->tanggal_lahir)
                                    {{ \Carbon\Carbon::parse($a->tanggal_lahir)->age }} Tahun
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $a->pekerjaan ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('user.anggota.edit', $a->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('user.anggota.destroy', $a->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
