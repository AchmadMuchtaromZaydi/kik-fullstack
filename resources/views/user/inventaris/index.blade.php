<div class="container py-4">

    {{-- Flash Message --}}
    @if(session('success_inventaris'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success_inventaris') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-1">Data Inventaris</h5>
            <p class="text-muted mb-3">
                Masukkan data inventaris organisasi Anda.
                <br>
                <strong>Minimal 5 inventaris</strong> diperlukan. <br>
                @if($inventaris->count() >= 5)
                    <span class="text-success">(Batas minimal terpenuhi)</span>
                @else
                    <span class="text-danger">(Saat ini baru {{ $inventaris->count() }})</span>
                @endif
            </p>

            {{-- Tombol Tambah Inventaris --}}
            @if($inventaris->count() < 5)
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalInventaris">
                <i class="bi bi-plus-circle me-1"></i> Tambah Inventaris
            </button>
            @endif

            {{-- Tabel Inventaris --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Tahun Pembelian</th>
                            <th>Kondisi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($inventaris as $index => $inv)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $inv->nama }}</td>
                            <td>{{ $inv->jumlah }}</td>
                            <td>{{ $inv->pembelian_th ?? '-' }}</td>
                            <td>{{ $inv->kondisi }}</td>
                            <td>{{ $inv->keterangan ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-info me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditInventaris{{ $inv->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteInventaris{{ $inv->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Belum ada data inventaris.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

               <div class="d-flex justify-content-between mt-3">

          <!-- Tombol Kembali -->
            <button class="btn btn-secondary prev-tab" data-prev="#tab-anggota">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </button>
            {{-- TOMBOL NEXT --}}
            <button
                id="btnNextInventaris"
                class="btn btn-success px-4 next-tab"
                data-next="#tab-pendukung"
                @if($inventaris->count() < 5) disabled @endif
            >
                Selanjutnya
            </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Inventaris --}}
<div class="modal fade" id="modalInventaris" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Inventaris</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.inventaris.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" min="1" required>
                        </div>
                       <div class="col-md-3">
                            <label>Tahun Pembelian</label>
                            <select name="pembelian_th" class="form-select">
                                <option value="">Pilih Tahun</option>
                                @for ($year = now()->year; $year >= 1990; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Kondisi</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="Baru">Baru</option>
                                <option value="Bekas">Bekas</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: dalam perbaikan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit & Delete --}}
@foreach($inventaris as $inv)
    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEditInventaris{{ $inv->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Inventaris</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.inventaris.update', $inv->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Nama</label>
                                <input type="text" name="nama" value="{{ $inv->nama }}" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" value="{{ $inv->jumlah }}" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label>Tahun Pembelian</label>
                                <select name="pembelian_th" class="form-select">
                                    <option value="">Pilih Tahun</option>
                                    @for ($year = now()->year; $year >= 1990; $year--)
                                        <option value="{{ $year }}" {{ $inv->pembelian_th == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Kondisi</label>
                                <select name="kondisi" class="form-select" required>
                                    <option value="Baru" {{ $inv->kondisi == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Bekas" {{ $inv->kondisi == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                                    <option value="Rusak" {{ $inv->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" value="{{ $inv->keterangan }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade" id="modalDeleteInventaris{{ $inv->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Inventaris</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.inventaris.destroy', $inv->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus inventaris <strong>{{ $inv->nama }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- ========================= --}}
{{-- Script supaya tab Inventaris aktif setelah reload --}}
{{-- ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(@json(session('tab')) === 'inventaris') {
        const tabButtons = document.querySelectorAll('#form-tabs button');
        const tabPanes = document.querySelectorAll('.tab-pane');

        // Sembunyikan semua tab lain
        tabPanes.forEach(tab => tab.classList.add('d-none'));

        // Tampilkan tab inventaris
        document.querySelector('#tab-inventaris').classList.remove('d-none');

        // Atur tombol tab aktif
        tabButtons.forEach(btn => btn.classList.remove('active'));
        const btnInventaris = document.querySelector('#form-tabs button[data-target="#tab-inventaris"]');
        if(btnInventaris) btnInventaris.classList.add('active');
    }
});
</script>
