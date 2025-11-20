<div class="container py-4">

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif


    @php
        // --- PERHITUNGAN AWAL (WAJIB DITARUH DI ATAS) ---
        $jumlahMaks = $jumlahMaks ?? ($organisasi->jumlah_anggota ?? 0);
        $jumlahSaatIni = $jumlahSaatIni ?? ($organisasi->anggota()->count() ?? 0);

        // Data ketua & sekretaris jika controller tidak mengirim
        $ketua = $ketua ?? ($anggota->where('jabatan', 'Ketua')->first() ?? null);
        $sekretaris = $sekretaris ?? ($anggota->where('jabatan', 'Sekretaris')->first() ?? null);

        // Status kelengkapan
        $isKetuaAda = !empty($ketua);
        $isSekretarisAda = !empty($sekretaris);
        $isJumlahComplete = $jumlahSaatIni >= $jumlahMaks;

        // Syarat tombol next
        $isComplete = $isJumlahComplete && $isKetuaAda && $isSekretarisAda;
    @endphp


    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="card-title fw-semibold mb-3">Data Anggota</h5>

            {{-- PERINGATAN KURANG --}}
            <div class="mb-3">
                @if(!$isKetuaAda)
                    <p class="text-danger mb-1">✦ Jabatan <strong>Ketua</strong> belum diisi.</p>
                @endif

                @if(!$isSekretarisAda)
                    <p class="text-danger mb-1">✦ Jabatan <strong>Sekretaris</strong> belum diisi.</p>
                @endif

                @if(!$isJumlahComplete)
                    <p class="text-danger mb-1">✦ Jumlah anggota belum memenuhi {{ $jumlahMaks }} orang.</p>
                @endif
            </div>


            <p class="text-muted mb-3">
                Masukkan data anggota dalam organisasi anda sejumlah
                <strong>{{ $jumlahMaks }} Orang</strong>.
                @if($jumlahSaatIni >= $jumlahMaks)
                    <span class="text-danger">(Jumlah anggota sudah penuh)</span>
                @endif
            </p>

            {{-- Tombol Tambah --}}
            @if($jumlahSaatIni < $jumlahMaks)
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAnggota">
                <i class="bi bi-plus-circle me-1"></i> Tambah Anggota
            </button>
            @endif


            {{-- TABLE ANGGOTA --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>L/P</th>
                            <th>Umur</th>
                            <th>Jabatan</th>
                            <th>Kontak</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody class="text-center text-muted">
                        @forelse($anggota as $index => $a)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $a->nik }}</td>
                            <td>{{ $a->nama }}</td>
                            <td>{{ $a->jenis_kelamin }}</td>
                           <td>
                                @php
                                    $umur = $a->tanggal_lahir ? \Carbon\Carbon::parse($a->tanggal_lahir)->age : null;
                                @endphp
                                {{ $umur !== null ? $umur . ' th' : '-' }}
                            </td>
                            <td>{{ $a->jabatan }}</td>
                            <td>{{ $a->telepon ?? $a->whatsapp ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-info me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditAnggota{{ $a->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeleteAnggota{{ $a->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">Belum ada data anggota.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">

          <!-- Tombol Kembali -->
            <button class="btn btn-secondary prev-tab" data-prev="#tab-organisasi">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </button>


            {{-- TOMBOL NEXT --}}

                <button
                    id="btnNextAnggota"
                    class="btn btn-success px-4 next-tab"
                    data-next="#tab-inventaris"
                    @if(!$isComplete) disabled @endif
                >
                    Selanjutnya
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ========================= --}}
{{-- Modal Tambah Anggota --}}
{{-- ========================= --}}
@if($jumlahSaatIni < $jumlahMaks)
<div class="modal fade" id="modalAnggota" tabindex="-1" aria-labelledby="modalAnggotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAnggotaLabel">Tambah Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.anggota.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="">Pilih</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Wakil">Wakil</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="Anggota">Anggota</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Jenis Kelamin</label><br>
                            <input type="radio" name="jenis_kelamin" value="L" required> Laki-Laki
                            <input type="radio" name="jenis_kelamin" value="P" required> Perempuan
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control">
                        </div>
                        <div class="col-12">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Whatsapp</label>
                            <input type="text" name="whatsapp" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ========================= --}}
{{-- Modal Edit Anggota --}}
{{-- ========================= --}}
@foreach($anggota as $a)
<div class="modal fade" id="modalEditAnggota{{ $a->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Edit Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.anggota.update', $a->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ $a->nama }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ $a->nik }}" required>
                        </div>
                        <div class="col-md-4">
                            <label>Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="Ketua" {{ $a->jabatan=='Ketua'?'selected':'' }}>Ketua</option>
                                <option value="Wakil" {{ $a->jabatan=='Wakil'?'selected':'' }}>Wakil</option>
                                <option value="Sekretaris" {{ $a->jabatan=='Sekretaris'?'selected':'' }}>Sekretaris</option>
                                <option value="Bendahara" {{ $a->jabatan=='Bendahara'?'selected':'' }}>Bendahara</option>
                                <option value="Anggota" {{ $a->jabatan=='Anggota'?'selected':'' }}>Anggota</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Jenis Kelamin</label><br>
                            <input type="radio" name="jenis_kelamin" value="L" {{ $a->jenis_kelamin=='L'?'checked':'' }} required> Laki-Laki
                            <input type="radio" name="jenis_kelamin" value="P" {{ $a->jenis_kelamin=='P'?'checked':'' }} required> Perempuan
                        </div>
                        <div class="col-md-4">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $a->tanggal_lahir }}">
                        </div>
                        <div class="col-md-4">
                            <label>Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control" value="{{ $a->pekerjaan }}">
                        </div>
                        <div class="col-12">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ $a->alamat }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="{{ $a->telepon }}">
                        </div>
                        <div class="col-md-6">
                            <label>Whatsapp</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ $a->whatsapp }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- ========================= --}}
{{-- Modal Delete Anggota --}}
{{-- ========================= --}}
@foreach($anggota as $a)
<div class="modal fade" id="modalDeleteAnggota{{ $a->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.anggota.destroy', $a->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    Apakah anda yakin ingin menghapus anggota <strong>{{ $a->nama }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- ========================= --}}
{{-- Script supaya tab Anggota aktif setelah reload --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(@json(session('tab')) === 'anggota') {
        const tabButtons = document.querySelectorAll('#form-tabs button');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabPanes.forEach(tab => tab.classList.add('d-none'));
        document.querySelector('#tab-anggota').classList.remove('d-none');

        tabButtons.forEach(btn => btn.classList.remove('active'));
        const btnAnggota = document.querySelector('#form-tabs button[data-target="#tab-anggota"]');
        if(btnAnggota) btnAnggota.classList.add('active');
    }
});
</script>
