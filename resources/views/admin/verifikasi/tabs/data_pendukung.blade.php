<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-alt me-2"></i>Dokumen Pendukung
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Foto KTP -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Foto KTP Ketua</h6>
                    </div>
                    <div class="card-body text-center">
                        @if ($organisasi->dokumen_ktp_url)
                            <img src="{{ $organisasi->dokumen_ktp_url }}" alt="Foto KTP" class="img-fluid rounded"
                                style="max-height:200px; cursor:pointer;"
                                onclick="openModal('{{ $organisasi->dokumen_ktp_url }}','Foto KTP Ketua')">
                            <div class="mt-2">
                                <span class="badge bg-success"><i class="fas fa-check"></i> File Ditemukan</span>
                            </div>
                            <small class="text-muted">{{ $organisasi->dokumen_ktp->image ?? '' }}</small>
                        @else
                            <div class="text-muted">
                                <i class="fas fa-id-card fa-3x mb-2"></i>
                                <p>Foto KTP belum diupload</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pas Foto -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Pas Foto 4x6</h6>
                    </div>
                    <div class="card-body text-center">
                        @if ($organisasi->dokumen_pas_foto_url)
                            <img src="{{ $organisasi->dokumen_pas_foto_url }}" alt="Pas Foto" class="img-fluid rounded"
                                style="max-height:200px; cursor:pointer;"
                                onclick="openModal('{{ $organisasi->dokumen_pas_foto_url }}','Pas Foto')">
                            <div class="mt-2">
                                <span class="badge bg-success"><i class="fas fa-check"></i> File Ditemukan</span>
                            </div>
                            <small class="text-muted">{{ $organisasi->dokumen_pas_foto->image ?? '' }}</small>
                        @else
                            <div class="text-muted">
                                <i class="fas fa-user fa-3x mb-2"></i>
                                <p>Pas Foto belum diupload</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Banner -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Banner Organisasi</h6>
                    </div>
                    <div class="card-body text-center">
                        @if ($organisasi->dokumen_banner_url)
                            <img src="{{ $organisasi->dokumen_banner_url }}" alt="Banner" class="img-fluid rounded"
                                style="max-height:200px; cursor:pointer;"
                                onclick="openModal('{{ $organisasi->dokumen_banner_url }}','Banner Organisasi')">
                            <div class="mt-2">
                                <span class="badge bg-success"><i class="fas fa-check"></i> File Ditemukan</span>
                            </div>
                            <small class="text-muted">{{ $organisasi->dokumen_banner->image ?? '' }}</small>
                        @else
                            <div class="text-muted">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p>Banner belum diupload</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Foto Kegiatan -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Foto Kegiatan</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $fotoKegiatan = $organisasi->dataPendukung->where('tipe', 'FOTO-KEGIATAN');
                        @endphp

                        @if ($fotoKegiatan->count() > 0)
                            <div class="row">
                                @foreach ($fotoKegiatan as $foto)
                                    @php
                                        $url = $organisasi->getFileUrl($foto);
                                    @endphp
                                    <div class="col-6 mb-2 text-center">
                                        @if ($url)
                                            <img src="{{ $url }}" alt="Foto Kegiatan" class="img-fluid rounded"
                                                style="height: 80px; width: 100%; object-fit: cover; cursor:pointer;"
                                                onclick="openModal('{{ $url }}', '{{ $foto->tipe }}')">
                                            <div class="text-center mt-1">
                                                <small class="text-muted">{{ basename($foto->image) }}</small>
                                            </div>
                                        @else
                                            <div class="text-center text-danger border rounded p-2">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <small>File tidak ada</small><br>
                                                <small>{{ $foto->image }}</small>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted text-center">
                                <i class="fas fa-camera fa-2x mb-2"></i>
                                <p>Foto kegiatan belum diupload</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <hr>

        <!-- Form Verifikasi -->
        <form action="{{ route('admin.verifikasi.store', $organisasi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="tipe" value="data_pendukung">

            <h5>Verifikasi Dokumen Pendukung</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                {{ ($verifikasiData->where('tipe', 'data_pendukung')->first()->status ?? '') == 'valid' ? 'selected' : '' }}>
                                Valid</option>
                            <option value="tdk_valid"
                                {{ ($verifikasiData->where('tipe', 'data_pendukung')->first()->status ?? '') == 'tdk_valid' ? 'selected' : '' }}>
                                Tidak Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2">{{ $verifikasiData->where('tipe', 'data_pendukung')->first()->catatan ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ $verifikasiData->where('tipe', 'data_pendukung')->first()->keterangan ?? '' }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_inventaris']) }}"
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

<!-- Modal Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <a href="#" id="downloadImage" class="btn btn-primary" download>
                    <i class="fas fa-download me-2"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(src, title) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModalLabel').textContent = title;
        document.getElementById('downloadImage').href = src;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>
