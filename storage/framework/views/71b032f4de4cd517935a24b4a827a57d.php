
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
                        <?php if($organisasi->dokumen_ktp): ?>
                            <?php if($organisasi->dokumen_ktp_file_exists && $organisasi->dokumen_ktp_url): ?>
                                <img src="<?php echo e($organisasi->dokumen_ktp_url); ?>" alt="Foto KTP" class="img-fluid rounded"
                                    style="max-height: 200px; cursor: pointer;"
                                    onclick="openModal('<?php echo e($organisasi->dokumen_ktp_url); ?>', 'Foto KTP')">
                                <div class="mt-2">
                                    <span
                                        class="badge bg-<?php echo e($organisasi->dokumen_ktp->validasi ? 'success' : 'warning'); ?>">
                                        <?php echo e($organisasi->dokumen_ktp->validasi ? 'Terverifikasi' : 'Belum Diverifikasi'); ?>

                                    </span>
                                    <span class="badge bg-success ms-1">
                                        <i class="fas fa-check"></i> File Ditemukan
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">Path: <?php echo e($organisasi->dokumen_ktp->image); ?></small>
                                </div>
                            <?php else: ?>
                                <div class="text-danger">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                                    <p>File tidak ditemukan di storage</p>
                                    <div class="mt-2">
                                        <small class="text-muted">Original Path:
                                            <?php echo e($organisasi->dokumen_ktp->image); ?></small><br>
                                        <small class="text-muted">Resolved Path:
                                            <?php echo e($organisasi->getFilePath($organisasi->dokumen_ktp) ?? 'Tidak ditemukan'); ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-muted">
                                <i class="fas fa-id-card fa-3x mb-2"></i>
                                <p>Foto KTP belum diupload</p>
                            </div>
                        <?php endif; ?>
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
                        <?php if($organisasi->dokumen_pas_foto): ?>
                            <?php if($organisasi->dokumen_pas_foto_file_exists && $organisasi->dokumen_pas_foto_url): ?>
                                <img src="<?php echo e($organisasi->dokumen_pas_foto_url); ?>" alt="Pas Foto"
                                    class="img-fluid rounded" style="max-height: 200px; cursor: pointer;"
                                    onclick="openModal('<?php echo e($organisasi->dokumen_pas_foto_url); ?>', 'Pas Foto')">
                                <div class="mt-2">
                                    <span
                                        class="badge bg-<?php echo e($organisasi->dokumen_pas_foto->validasi ? 'success' : 'warning'); ?>">
                                        <?php echo e($organisasi->dokumen_pas_foto->validasi ? 'Terverifikasi' : 'Belum Diverifikasi'); ?>

                                    </span>
                                    <span class="badge bg-success ms-1">
                                        <i class="fas fa-check"></i> File Ditemukan
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">Path: <?php echo e($organisasi->dokumen_pas_foto->image); ?></small>
                                </div>
                            <?php else: ?>
                                <div class="text-danger">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                                    <p>File tidak ditemukan di storage</p>
                                    <div class="mt-2">
                                        <small class="text-muted">Original Path:
                                            <?php echo e($organisasi->dokumen_pas_foto->image); ?></small><br>
                                        <small class="text-muted">Resolved Path:
                                            <?php echo e($organisasi->getFilePath($organisasi->dokumen_pas_foto) ?? 'Tidak ditemukan'); ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-muted">
                                <i class="fas fa-user fa-3x mb-2"></i>
                                <p>Pas Foto belum diupload</p>
                            </div>
                        <?php endif; ?>
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
                        <?php if($organisasi->dokumen_banner): ?>
                            <?php if($organisasi->dokumen_banner_file_exists && $organisasi->dokumen_banner_url): ?>
                                <img src="<?php echo e($organisasi->dokumen_banner_url); ?>" alt="Banner"
                                    class="img-fluid rounded" style="max-height: 200px; cursor: pointer;"
                                    onclick="openModal('<?php echo e($organisasi->dokumen_banner_url); ?>', 'Banner')">
                                <div class="mt-2">
                                    <span
                                        class="badge bg-<?php echo e($organisasi->dokumen_banner->validasi ? 'success' : 'warning'); ?>">
                                        <?php echo e($organisasi->dokumen_banner->validasi ? 'Terverifikasi' : 'Belum Diverifikasi'); ?>

                                    </span>
                                    <span class="badge bg-success ms-1">
                                        <i class="fas fa-check"></i> File Ditemukan
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">Path: <?php echo e($organisasi->dokumen_banner->image); ?></small>
                                </div>
                            <?php else: ?>
                                <div class="text-danger">
                                    <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                                    <p>File tidak ditemukan di storage</p>
                                    <div class="mt-2">
                                        <small class="text-muted">Original Path:
                                            <?php echo e($organisasi->dokumen_banner->image); ?></small><br>
                                        <small class="text-muted">Resolved Path:
                                            <?php echo e($organisasi->getFilePath($organisasi->dokumen_banner) ?? 'Tidak ditemukan'); ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-muted">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p>Banner belum diupload</p>
                            </div>
                        <?php endif; ?>
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
                        <?php if($organisasi->dokumen_kegiatan->count() > 0): ?>
                            <div class="row">
                                <?php
                                    $fotoKegiatanData = $organisasi->getFotoKegiatanWithStatus();
                                    $totalFotos = count($fotoKegiatanData);
                                    $foundCount = 0;
                                    $verifiedCount = 0;
                                ?>

                                <?php $__currentLoopData = $fotoKegiatanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fotoData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        if ($fotoData['exists']) {
                                            $foundCount++;
                                        }
                                        if ($fotoData['foto']->validasi) {
                                            $verifiedCount++;
                                        }
                                    ?>
                                    <div class="col-6 mb-2">
                                        <?php if($fotoData['exists'] && $fotoData['url']): ?>
                                            <img src="<?php echo e($fotoData['url']); ?>" alt="Foto Kegiatan"
                                                class="img-fluid rounded"
                                                style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;"
                                                onclick="openModal('<?php echo e($fotoData['url']); ?>', 'Foto Kegiatan <?php echo e($fotoData['index'] + 1); ?>')">
                                            <div class="text-center mt-1">
                                                <span
                                                    class="badge bg-<?php echo e($fotoData['foto']->validasi ? 'success' : 'warning'); ?> badge-sm">
                                                    <?php echo e($fotoData['foto']->validasi ? '✓' : '●'); ?>

                                                </span>
                                            </div>
                                            <div class="text-center">
                                                <small
                                                    class="text-muted"><?php echo e(basename($fotoData['foto']->image)); ?></small>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center text-danger border rounded p-2">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <small>File tidak ada</small>
                                                <br>
                                                <small
                                                    class="text-muted"><?php echo e($fotoData['foto']->image ?? 'No file'); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-info"><?php echo e($totalFotos); ?> foto</span>
                                <span class="badge bg-<?php echo e($verifiedCount == $totalFotos ? 'success' : 'warning'); ?>">
                                    <?php echo e($verifiedCount); ?>/<?php echo e($totalFotos); ?> Terverifikasi
                                </span>
                                <span class="badge bg-<?php echo e($foundCount == $totalFotos ? 'success' : 'danger'); ?>">
                                    <?php echo e($foundCount); ?>/<?php echo e($totalFotos); ?> File Ditemukan
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="text-muted text-center">
                                <i class="fas fa-camera fa-2x mb-2"></i>
                                <p>Foto kegiatan belum diupload</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Debug Information -->
            <div class="card mt-4">
                <div class="card-header bg-warning">
                    <h6 class="card-title mb-0">Informasi Debug Storage</h6>
                    <button type="button" class="btn btn-sm btn-primary float-end" onclick="checkStorage()">
                        <i class="fas fa-sync-alt"></i> Check Storage
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Base Storage Path:</strong> <?php echo e(storage_path('app')); ?><br>
                            <strong>Public Path:</strong> <?php echo e(public_path()); ?><br>
                            <strong>Storage URL:</strong> <?php echo e(Storage::url('test')); ?><br>
                            <strong>Organisasi ID:</strong> <?php echo e($organisasi->id); ?><br>
                        </div>
                        <div class="col-md-6">
                            <strong>Public Storage Path:</strong> <?php echo e(storage_path('app/public')); ?><br>
                            <strong>Uploads Path:</strong> <?php echo e(storage_path('app/public/uploads')); ?><br>
                            <strong>Organisasi Uploads Path:</strong>
                            <?php echo e(storage_path('app/public/uploads/organisasi/' . $organisasi->id)); ?><br>
                        </div>
                    </div>

                    <?php if($organisasi->dokumen_ktp): ?>
                        <hr>
                        <h6>Debug KTP:</h6>
                        <strong>Original Path:</strong> <?php echo e($organisasi->dokumen_ktp->image); ?><br>
                        <strong>Resolved Path:</strong>
                        <?php echo e($organisasi->getFilePath($organisasi->dokumen_ktp) ?? 'Tidak ditemukan'); ?><br>
                        <strong>File Exists:</strong>
                        <?php echo e($organisasi->getFileExists($organisasi->dokumen_ktp) ? 'Ya' : 'Tidak'); ?><br>
                        <strong>URL:</strong>
                        <?php echo e($organisasi->getFileUrl($organisasi->dokumen_ktp) ?? 'Tidak ada URL'); ?><br>
                    <?php endif; ?>
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
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk internal admin"><?php echo e($verifikasiData->where('tipe', 'data_pendukung')->first()->catatan ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan untuk Pendaftar</label>
                    <textarea name="keterangan" class="form-control" rows="3"
                        placeholder="Keterangan yang akan dilihat oleh pendaftar"><?php echo e($verifikasiData->where('tipe', 'data_pendukung')->first()->keterangan ?? ''); ?></textarea>
                    <small class="text-muted">Contoh: "Semua dokumen sudah lengkap" atau "Pas foto perlu diganti dengan
                        latar merah"</small>
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

    <!-- Modal untuk preview gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        function openModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModalLabel').textContent = title;
            document.getElementById('downloadImage').href = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        // Debug function untuk mengecek storage
        function checkStorage() {
            fetch('<?php echo e(route('admin.verifikasi.status', $organisasi->id)); ?>')
                .then(response => response.json())
                .then(data => {
                    console.log('Storage check:', data);
                    alert('Storage check completed. Check console for details.');
                })
                .catch(error => {
                    console.error('Storage check error:', error);
                    alert('Error checking storage: ' + error.message);
                });
        }

        // Panggil saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Debug Info:', <?php echo json_encode($debugInfo, 15, 512) ?>);
        });
    </script>
</div>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/data_pendukung.blade.php ENDPATH**/ ?>