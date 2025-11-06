@extends('layouts.app')
@section('title', 'Edit Inventaris')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Data Inventaris</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('user.inventaris.update', $inventaris->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" class="form-control" value="{{ $inventaris->nama }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" value="{{ $inventaris->jumlah }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Pembelian</label>
                    <input type="number" name="pembelian_th" class="form-control" value="{{ $inventaris->pembelian_th }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-select" required>
                        <option value="Baru" {{ $inventaris->kondisi == 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Bekas" {{ $inventaris->kondisi == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                        <option value="Rusak" {{ $inventaris->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control">{{ $inventaris->keterangan }}</textarea>
                </div>

                <div class="text-end">
                    <a href="{{ route('user.inventaris.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
