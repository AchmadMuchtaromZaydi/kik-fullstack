{{-- resources/views/admin/verifikasi/tabs/data_anggota.blade.php --}}
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>Data Anggota ({{ $organisasi->anggota->count() }})
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>L/P</th>
                        <th>Umur</th>
                        <th>Pekerjaan</th>
                        <th>Jabatan</th>
                        <th>Kontak</th>
                        <th>Status Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organisasi->anggota as $index => $anggota)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $anggota->nik ?? '-' }}</td>
                            <td>{{ $anggota->nama }}</td>
                            <td>{{ $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>
                                @if ($anggota->tanggal_lahir)
                                    {{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->age }} th
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $anggota->pekerjaan ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $anggota->jabatan == 'Ketua' ? 'primary' : ($anggota->jabatan == 'Sekretaris' ? 'success' : 'secondary') }}">
                                    {{ $anggota->jabatan }}
                                </span>
                            </td>
                            <td>{{ $anggota->telepon ?? ($anggota->whatsapp ?? '-') }}</td>
                            <td>
                                <span class="badge bg-{{ $anggota->validasi ? 'success' : 'warning' }}">
                                    {{ $anggota->validasi ? 'Terverifikasi' : 'Belum Diverifikasi' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    @if ($organisasi->anggota->count() == 0)
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data anggota</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            @php
                $ketua = $organisasi->anggota->where('jabatan', 'Ketua')->first();
                $sekretaris = $organisasi->anggota->where('jabatan', 'Sekretaris')->first();
            @endphp
            <strong>Struktur Kepengurusan:</strong>
            @if ($ketua)
                <span class="badge bg-primary">Ketua: {{ $ketua->nama }}</span>
            @else
                <span class="badge bg-danger">Ketua: Belum Ada</span>
            @endif

            @if ($sekretaris)
                <span class="badge bg-success ms-2">Sekretaris: {{ $sekretaris->nama }}</span>
            @else
                <span class="badge bg-danger ms-2">Sekretaris: Belum Ada</span>
            @endif
        </div>

        <hr>

        <form action="{{ route('admin.verifikasi.store', $organisasi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="tipe" value="data_anggota">

            <h5>Verifikasi Data Anggota</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                {{ ($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'valid' ? 'selected' : '' }}>
                                Valid</option>
                            <option value="tdk_valid"
                                {{ ($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'tdk_valid' ? 'selected' : '' }}>
                                Tidak Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk internal admin">{{ $verifikasiData->where('tipe', 'data_anggota')->first()->catatan ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar">{{ $verifikasiData->where('tipe', 'data_anggota')->first()->keterangan ?? '' }}</textarea>
                <small class="text-muted">Contoh: "Data anggota sudah lengkap" atau "Perlu melengkapi data
                    sekretaris"</small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi']) }}"
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
