{{-- resources/views/admin/verifikasi/tabs/data_organisasi.blade.php --}}
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-building me-2"></i>Data Organisasi
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Nama Organisasi</th>
                        <td>{{ $organisasi->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Induk</th>
                        <td>
                            @if (!empty($organisasi->nomor_induk))
                                <span class="badge bg-success">{{ $organisasi->nomor_induk }}</span>
                            @else
                                <span class="badge bg-warning">Belum ada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Berdiri</th>
                        <td>
                            @if ($organisasi->tanggal_berdiri)
                                {{ \Carbon\Carbon::parse($organisasi->tanggal_berdiri)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Kesenian</th>
                        <td>
                            <strong>{{ $organisasi->jenis_kesenian_nama ?? '-' }}</strong>
                            @if (!empty($organisasi->sub_kesenian_nama) && $organisasi->sub_kesenian_nama != 'Tidak ada sub jenis')
                                <br><small class="text-muted">Sub: {{ $organisasi->sub_kesenian_nama }}</small>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Jumlah Anggota</th>
                        <td>{{ $organisasi->jumlah_anggota ?? 0 }} orang</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $organisasi->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kecamatan</th>
                        <td>{{ $organisasi->nama_kecamatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td>{{ $organisasi->nama_desa ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{!! $organisasi->status_badge ?? '<span class="badge bg-secondary">-</span>' !!}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Data Ketua -->
        <div class="mt-4">
            <h6>Data Ketua Organisasi</h6>
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Nama Ketua</th>
                    <td>{{ $organisasi->nama_ketua ?? '-' }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $organisasi->no_telp_ketua ?? '-' }}</td>
                </tr>
            </table>

            @if (empty($organisasi->nama_ketua) || $organisasi->nama_ketua == '-')
                <div class="alert alert-warning mt-2">
                    <small>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Data ketua belum ditemukan. Pastikan ada anggota dengan jabatan "Ketua".
                    </small>
                </div>
            @endif
        </div>

        <hr>

        <form action="{{ route('admin.verifikasi.store', $organisasi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="tipe" value="data_organisasi">

            <h5>Verifikasi Data Organisasi</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                {{ ($verifikasiData->where('tipe', 'data_organisasi')->first()->status ?? '') == 'valid' ? 'selected' : '' }}>
                                Valid</option>
                            <option value="tdk_valid"
                                {{ ($verifikasiData->where('tipe', 'data_organisasi')->first()->status ?? '') == 'tdk_valid' ? 'selected' : '' }}>
                                Tidak Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk internal admin">{{ $verifikasiData->where('tipe', 'data_organisasi')->first()->catatan ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar">{{ $verifikasiData->where('tipe', 'data_organisasi')->first()->keterangan ?? '' }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'general']) }}"
                    class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan & Lanjutkan <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
