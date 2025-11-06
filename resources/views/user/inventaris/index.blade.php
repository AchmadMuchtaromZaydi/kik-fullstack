@extends('layouts.app')
@section('title', 'Data Inventaris')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Inventaris Barang</h5>

        {{-- Tombol tambah inventaris hanya muncul jika jumlah < 5 --}}
        @if($inventaris->count() < 5)
            <a href="{{ route('user.inventaris.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus-circle"></i> Tambah Inventaris
            </a>
        @else
            <span class="badge bg-warning text-dark">Kuota inventaris sudah penuh</span>
        @endif
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Peringatan jumlah inventaris --}}
        @if($inventaris->count() < 5)
            <div class="alert alert-info">Silakan tambahkan data inventaris minimal sampai 5 item.</div>
        @elseif($inventaris->count() >= 5)
            <div class="alert alert-danger">Jumlah inventaris sudah mencapai batas maksimal 5 item.</div>
        @endif

        @if($inventaris->isEmpty())
            <div class="alert alert-info">Belum ada data inventaris barang.</div>
        @else
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Tahun Pembelian</th>
                        <th>Kondisi</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventaris as $index => $inv)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $inv->nama }}</td>
                        <td class="text-center">{{ $inv->jumlah }}</td>
                        <td class="text-center">{{ $inv->pembelian_th ?? '-' }}</td>
                        <td class="text-center">
                            @if($inv->kondisi == 'Baru')
                                <span class="badge bg-success">{{ $inv->kondisi }}</span>
                            @elseif($inv->kondisi == 'Bekas')
                                <span class="badge bg-warning text-dark">{{ $inv->kondisi }}</span>
                            @else
                                <span class="badge bg-danger">{{ $inv->kondisi }}</span>
                            @endif
                        </td>
                        <td>{{ $inv->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('user.inventaris.edit', $inv->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>

                            <form action="{{ route('user.inventaris.destroy', $inv->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus inventaris ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
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
