@extends('layouts.app')
@section('title', 'Edit Anggota')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Data Anggota</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.anggota.update', $anggota->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $anggota->nama) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $anggota->nik) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jabatan</label>
                    <select name="jabatan" class="form-select" required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach (['Ketua','Wakil Ketua','Sekretaris','Bendahara','Anggota'] as $jab)
                            <option value="{{ $jab }}" {{ old('jabatan', $anggota->jabatan) == $jab ? 'selected' : '' }}>{{ $jab }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $anggota->pekerjaan) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control">{{ old('alamat', $anggota->alamat) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $anggota->telepon) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">No WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $anggota->whatsapp) }}" class="form-control">
                    <small class="form-text text-muted">Contoh: nomor wa : 081234421112</small>
                </div>

                <div class="text-end">
                    <a href="{{ route('user.anggota.index') }}" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-warning">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
