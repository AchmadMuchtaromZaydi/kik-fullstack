{{-- resources/views/admin/verifikasi/tabs/data_anggota.blade.php --}}
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>Data Anggota
            <span class="badge bg-light text-dark">({{ $organisasi->anggota->count() }})</span>
        </h5>
    </div>
    <div class="card-body">

        {{-- Debug Info --}}
        @if ($organisasi->anggota->count() == 0)
            <div class="alert alert-warning">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Debug Information</h6>
                <p><strong>Organisasi ID:</strong> {{ $organisasi->id }}</p>
                <p><strong>Nama Organisasi:</strong> {{ $organisasi->nama }}</p>
                <p><strong>Jumlah Anggota di Database:</strong>
                    @php
                        $countFromDB = \App\Models\Anggota::where('organisasi_id', $organisasi->id)->count();
                    @endphp
                    {{ $countFromDB }}
                </p>
                <p><strong>Relasi Loaded:</strong> {{ $organisasi->relationLoaded('anggota') ? 'Ya' : 'Tidak' }}</p>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th width="80">L/P</th>
                        <th width="80">Umur</th>
                        <th>Pekerjaan</th>
                        <th>Jabatan</th>
                        <th>Kontak</th>
                        <th width="120">Status Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($organisasi->anggota as $index => $anggota)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                @if ($anggota->nik)
                                    <code>{{ $anggota->nik }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $anggota->nama }}</strong>
                                @if ($anggota->jabatan == 'Ketua')
                                    <span class="badge bg-primary ms-1">Ketua</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($anggota->jenis_kelamin == 'L')
                                    <span class="badge bg-info">L</span>
                                @else
                                    <span class="badge bg-pink">P</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($anggota->tanggal_lahir)
                                    <span class="badge bg-secondary">
                                        {{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->age }} th
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $anggota->pekerjaan ?? '-' }}</td>
                            <td>
                                @php
                                    $jabatanColors = [
                                        'Ketua' => 'primary',
                                        'Sekretaris' => 'success',
                                        'Bendahara' => 'warning',
                                        'Wakil Ketua' => 'info',
                                        'Anggota' => 'secondary',
                                    ];
                                    $color = $jabatanColors[$anggota->jabatan] ?? 'dark';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ $anggota->jabatan }}
                                </span>
                            </td>
                            <td>
                                @if ($anggota->telepon || $anggota->whatsapp)
                                    <div>
                                        @if ($anggota->telepon)
                                            <small>Tel: {{ $anggota->telepon }}</small><br>
                                        @endif
                                        @if ($anggota->whatsapp)
                                            <small>WA: {{ $anggota->whatsapp }}</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($anggota->validasi)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>Belum Diverifikasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-2x mb-3"></i>
                                    <br>
                                    <strong>Tidak ada data anggota</strong>
                                    <br>
                                    <small>Belum ada anggota yang terdaftar untuk organisasi ini</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Struktur Kepengurusan --}}
        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="mb-3"><i class="fas fa-sitemap me-2"></i>Struktur Kepengurusan</h6>
            @php
                $ketua = $organisasi->anggota->where('jabatan', 'Ketua')->first();
                $sekretaris = $organisasi->anggota->where('jabatan', 'Sekretaris')->first();
                $bendahara = $organisasi->anggota->where('jabatan', 'Bendahara')->first();
            @endphp

            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2">Ketua</span>
                        @if ($ketua)
                            <span>{{ $ketua->nama }}</span>
                        @else
                            <span class="text-danger">Belum Ada</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2">Sekretaris</span>
                        @if ($sekretaris)
                            <span>{{ $sekretaris->nama }}</span>
                        @else
                            <span class="text-danger">Belum Ada</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-2">Bendahara</span>
                        @if ($bendahara)
                            <span>{{ $bendahara->nama }}</span>
                        @else
                            <span class="text-muted">Opsional</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="mt-3 pt-3 border-top">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Total Anggota:</strong> {{ $organisasi->anggota->count() }} orang
                    </div>
                    <div class="col-md-6">
                        <strong>Target Anggota:</strong> {{ $organisasi->jumlah_anggota }} orang
                    </div>
                </div>
                @if ($organisasi->anggota->count() < $organisasi->jumlah_anggota)
                    <div class="alert alert-warning mt-2 mb-0">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Jumlah anggota belum memenuhi target (kurang
                            {{ $organisasi->jumlah_anggota - $organisasi->anggota->count() }} orang)
                        </small>
                    </div>
                @endif
            </div>
        </div>

        <hr>

        {{-- Form Verifikasi --}}
        <form action="{{ route('admin.verifikasi.store', $organisasi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="tipe" value="data_anggota">

            <h5><i class="fas fa-clipboard-check me-2"></i>Verifikasi Data Anggota</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status Verifikasi</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                {{ ($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'valid' ? 'selected' : '' }}>
                                ✅ Valid - Data anggota lengkap dan sesuai
                            </option>
                            <option value="tdk_valid"
                                {{ ($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'tdk_valid' ? 'selected' : '' }}>
                                ❌ Tidak Valid - Ada masalah dengan data anggota
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Keputusan Berdasarkan</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" checked disabled>
                            <label class="form-check-label">
                                Jumlah anggota ({{ $organisasi->anggota->count() }}/{{ $organisasi->jumlah_anggota }})
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                {{ $ketua ? 'checked' : '' }} disabled>
                            <label class="form-check-label">
                                Struktur ketua {{ $ketua ? '✓' : '✗' }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                {{ $sekretaris ? 'checked' : '' }} disabled>
                            <label class="form-check-label">
                                Struktur sekretaris {{ $sekretaris ? '✓' : '✗' }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-sticky-note me-1"></i>Catatan Internal (Hanya untuk admin)
                </label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan internal untuk tim verifikasi...">{{ $verifikasiData->where('tipe', 'data_anggota')->first()->catatan ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-comment-dots me-1"></i>Keterangan untuk Pendaftar
                </label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar organisasi...">{{ $verifikasiData->where('tipe', 'data_anggota')->first()->keterangan ?? '' }}</textarea>
                <small class="text-muted">
                    Contoh: "Data anggota sudah lengkap dan valid" atau "Perlu melengkapi data sekretaris dan menambah
                    jumlah anggota"
                </small>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi']) }}"
                    class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Data Organisasi
                </a>

                <div>
                    @if ($organisasi->anggota->count() > 0)
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan & Lanjutkan
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    @else
                        <button type="button" class="btn btn-danger" disabled>
                            <i class="fas fa-exclamation-triangle me-2"></i>Tidak bisa verifikasi - Tidak ada anggota
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-pink {
        background-color: #e83e8c !important;
    }
</style>
