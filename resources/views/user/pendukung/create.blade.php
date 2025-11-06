@extends('layouts.app')
@section('title', 'Upload Data Pendukung')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-image me-2"></i>Upload Data Pendukung</h5>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('user.pendukung.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Jenis Data</label>
                    <select name="tipe" class="form-select" required>
                        <option value="">-- Pilih Jenis Data --</option>
                        <option value="ktp">Foto KTP</option>
                        <option value="photo">Pas Photo 3x4</option>
                        <option value="banner">Banner Organisasi</option>
                        <option value="poster">Poster Organisasi</option>
                        <option value="kegiatan">Foto Kegiatan</option>
                    </select>
                    @error('tipe') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Gambar</label>
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png" required>
                    <small class="text-muted">
                        Format gambar: JPG/PNG — Maksimal 2MB
                    </small>
                    @error('image') <small class="text-danger d-block">{{ $message }}</small> @enderror
                </div>

              <div class="alert alert-warning">
                    Pastikan foto sesuai dengan tipe yang dipilih!
                    Misalnya:
                    <ul class="mb-0">
                        <li>KTP: foto identitas jelas</li>
                        <li>Pas foto: tampak wajah 3x4</li>
                        <li>Banner / Poster: mengandung logo atau nama organisasi</li>
                        <li>Kegiatan: hanya satu foto kegiatan</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload
                </button>
                <a href="{{ route('user.pendukung.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
