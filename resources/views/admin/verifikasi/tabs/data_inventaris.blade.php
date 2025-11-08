{{-- resources/views/admin/verifikasi/tabs/data_inventaris.blade.php --}}
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-boxes me-2"></i>Data Inventaris Barang
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Tahun Pembelian</th>
                        <th>Kondisi</th>
                        <th>Keterangan</th>
                        <th>Status Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventaris as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->pembelian_th ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $item->kondisi == 'Baik' ? 'success' : ($item->kondisi == 'Rusak' ? 'danger' : 'warning') }}">
                                    {{ $item->kondisi ?? 'Tidak Diketahui' }}
                                </span>
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->validasi ? 'success' : 'warning' }}">
                                    {{ $item->validasi ? 'Terverifikasi' : 'Belum Diverifikasi' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data inventaris</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <strong>Total Barang:</strong> {{ $inventaris->count() }} item
            @if ($inventaris->count() > 0)
                | <strong>Total Jumlah:</strong> {{ $inventaris->sum('jumlah') }} unit
            @endif
        </div>

        <hr>

        <form action="{{ route('admin.verifikasi.store', $organisasi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="tipe" value="data_inventaris">

            <h5>Verifikasi Data Inventaris</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                {{ ($verifikasiData->where('tipe', 'data_inventaris')->first()->status ?? '') == 'valid' ? 'selected' : '' }}>
                                Valid</option>
                            <option value="tdk_valid"
                                {{ ($verifikasiData->where('tipe', 'data_inventaris')->first()->status ?? '') == 'tdk_valid' ? 'selected' : '' }}>
                                Tidak Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk internal admin">{{ $verifikasiData->where('tipe', 'data_inventaris')->first()->catatan ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar">{{ $verifikasiData->where('tipe', 'data_inventaris')->first()->keterangan ?? '' }}</textarea>
                <small class="text-muted">Contoh: "Data inventaris sudah lengkap dan valid" atau "Perlu melengkapi data
                    inventaris"</small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_anggota']) }}"
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
