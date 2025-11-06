@extends('layouts.app')

@section('title', 'Tambah Data Organisasi')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Data Organisasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('user.organisasi.store') }}" method="POST">
                @csrf

                {{-- Nama Organisasi --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Organisasi</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama organisasi" required>
                </div>

                {{-- Tanggal Berdiri --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Berdiri</label>
                    <input type="date" name="tanggal_berdiri" class="form-control" required>
                </div>

                {{-- Jenis & Sub Jenis Kesenian --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kesenian</label>
                    <select name="jenis_kesenian" id="jenis_kesenian" class="form-select" required>
                        <option value="">-- Pilih Jenis Kesenian --</option>
                        @foreach($jenisKesenian as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sub Jenis Kesenian</label>
                    <select name="sub_kesenian" id="sub_kesenian" class="form-select" required>
                        <option value="">-- Pilih Sub Jenis Kesenian --</option>
                    </select>
                </div>

                {{-- Jumlah Anggota --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Anggota</label>
                    <input type="number" name="jumlah_anggota" class="form-control" placeholder="Masukkan jumlah anggota" required>
                </div>

                {{-- Alamat Organisasi --}}
                <hr>
                <h6 class="fw-bold mb-3">Alamat Sekretariat Organisasi</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kabupaten</label>
                    <select name="kabupaten_kode" id="kabupaten" class="form-select" required>
                        <option value="">-- Pilih Kabupaten --</option>
                        @foreach($kabupaten as $k)
                            <option value="{{ $k->kode }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kecamatan</label>
                    <select name="kecamatan_kode" id="kecamatan" class="form-select" required>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Desa</label>
                    <select name="desa_kode" id="desa" class="form-select" required>
                        <option value="">-- Pilih Desa --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control" rows="3" placeholder="Tulis alamat sekretariat lengkap..." required></textarea>
                </div>

                {{-- Tombol Aksi --}}
                <div class="text-end">
                    <a href="{{ route('user.organisasi.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script AJAX --}}
@push('scripts')
<script>
const subUrl = "{{ route('user.organisasi.subkesenian', ':id') }}";
const kecUrl = "{{ route('user.organisasi.kecamatan', ':kode') }}";
const desaUrl = "{{ route('user.organisasi.desa', ':kode') }}";

// Sub Jenis
document.getElementById('jenis_kesenian').addEventListener('change', function() {
    const parentId = this.value;
    const subSelect = document.getElementById('sub_kesenian');
    subSelect.innerHTML = '<option>Memuat...</option>';

    fetch(subUrl.replace(':id', parentId))
        .then(res => res.json())
        .then(data => {
            subSelect.innerHTML = '<option value="">-- Pilih Sub Jenis Kesenian --</option>';
            data.forEach(sub => {
                subSelect.innerHTML += `<option value="${sub.id}">${sub.nama}</option>`;
            });
        })
        .catch(() => subSelect.innerHTML = '<option value="">Gagal memuat data</option>');
});

// Kecamatan (berdasarkan kabupaten)
document.getElementById('kabupaten').addEventListener('change', function() {
    const kode = this.value;
    const kecSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa');
    kecSelect.innerHTML = '<option>Memuat...</option>';
    desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';

    fetch(kecUrl.replace(':kode', kode))
        .then(res => res.json())
        .then(data => {
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            data.forEach(kec => {
                kecSelect.innerHTML += `<option value="${kec.kode}">${kec.nama}</option>`;
            });
        })
        .catch(() => kecSelect.innerHTML = '<option value="">Gagal memuat data</option>');
});

// Desa (berdasarkan kecamatan)
document.getElementById('kecamatan').addEventListener('change', function() {
    const kode = this.value;
    const desaSelect = document.getElementById('desa');
    desaSelect.innerHTML = '<option>Memuat...</option>';

    fetch(desaUrl.replace(':kode', kode))
        .then(res => res.json())
        .then(data => {
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            data.forEach(d => {
                desaSelect.innerHTML += `<option value="${d.kode}">${d.nama}</option>`;
            });
        })
        .catch(() => desaSelect.innerHTML = '<option value="">Gagal memuat data</option>');
});
</script>
@endpush
@endsection
