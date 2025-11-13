<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-file-alt me-2"></i>Dokumen Pendukung
        </h5>
    </div>
    <div class="card-body">
        <div class="row">

            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Foto KTP Ketua</h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if($organisasi->dokumen_ktp_url): ?>
                            <img src="<?php echo e($organisasi->dokumen_ktp_url); ?>" alt="Foto KTP"
                                class="img-fluid rounded shadow-sm" style="max-height:200px; cursor:pointer;"
                                onclick="openModal('<?php echo e($organisasi->dokumen_ktp_url); ?>','Foto KTP Ketua')">
                            <div class="mt-2">
                                <span class="badge bg-success"><i class="fas fa-check"></i> File Ditemukan</span>
                            </div>
                            <small class="text-muted"><?php echo e($organisasi->dokumen_ktp->image ?? ''); ?></small>
                        <?php else: ?>
                            <div class="text-muted">
                                <i class="fas fa-id-card fa-3x mb-2"></i>
                                <p>Foto KTP belum diupload</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Pas Foto 4x6</h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if($organisasi->dokumen_pas_foto_url): ?>
                            <img src="<?php echo e($organisasi->dokumen_pas_foto_url); ?>" alt="Pas Foto"
                                class="img-fluid rounded shadow-sm" style="max-height:200px; cursor:pointer;"
                                onclick="openModal('<?php echo e($organisasi->dokumen_pas_foto_url); ?>','Pas Foto')">
                            <div class="mt-2">
                                <span class="badge bg-success"><i class="fas fa-check"></i> File Ditemukan</span>
                            </div>
                            <small class="text-muted"><?php echo e($organisasi->dokumen_pas_foto->image ?? ''); ?></small>
                        <?php else: ?>
                            <div class="text-muted">
                                <i class="fas fa-user fa-3x mb-2"></i>
                                <p>Pas Foto belum diupload</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Banner Organisasi</h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if($organisasi->dokumen_banner_url): ?>
                            <img src="<?php echo e($organisasi->dokumen_banner_url); ?>" alt="Banner"
                                class="img-fluid rounded shadow-sm" style="max-height:200px; cursor:pointer;"
                                onclick="openModal('<?php echo e($organisasi->dokumen_banner_url); ?>','Banner Organisasi')">
                            <div class="mt-2">
                                <span class="badge bg-success"><i class="fas fa-check"></i> File Ditemukan</span>
                            </div>
                            <small class="text-muted"><?php echo e($organisasi->dokumen_banner->image ?? ''); ?></small>
                        <?php else: ?>
                            <div class="text-muted">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p>Banner belum diupload</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="card-title mb-0">Foto Kegiatan</h6>
                    </div>
                    <div class="card-body">
                        <?php
                            $fotoKegiatan = $organisasi->dataPendukung->where('tipe', 'FOTO-KEGIATAN');
                        ?>

                        <?php if($fotoKegiatan->count() > 0): ?>
                            <div class="row">
                                <?php $__currentLoopData = $fotoKegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $url = $organisasi->getFileUrl($foto); ?>
                                    <div class="col-md-3 col-sm-4 mb-3 text-center">
                                        <?php if($url): ?>
                                            <img src="<?php echo e($url); ?>" alt="Foto Kegiatan"
                                                class="img-fluid rounded shadow-sm border"
                                                style="height:120px; width:100%; object-fit:cover; cursor:pointer;"
                                                onclick="openModal('<?php echo e($url); ?>', 'Foto Kegiatan')">
                                            <div class="text-center mt-1">
                                                <small class="text-muted"><?php echo e(basename($foto->image)); ?></small>
                                            </div>
                                        <?php else: ?>
                                            <div class="border rounded p-2 text-danger">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <small>File tidak ditemukan</small><br>
                                                <small><?php echo e($foto->image); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-camera fa-2x mb-2"></i>
                                <p>Belum ada foto kegiatan</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <hr>

        
        <form action="<?php echo e(route('admin.verifikasi.store', $organisasi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipe" value="data_pendukung">

            <h5>Verifikasi Dokumen Pendukung</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_pendukung')->first()->status ?? '') == 'valid' ? 'selected' : ''); ?>>
                                Valid</option>
                            <option value="tdk_valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_pendukung')->first()->status ?? '') == 'tdk_valid' ? 'selected' : ''); ?>>
                                Tidak Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2"><?php echo e($verifikasiData->where('tipe', 'data_pendukung')->first()->catatan ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"><?php echo e($verifikasiData->where('tipe', 'data_pendukung')->first()->keterangan ?? ''); ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_inventaris'])); ?>"
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Preview" class="img-fluid rounded">
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
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/data_pendukung.blade.php ENDPATH**/ ?>