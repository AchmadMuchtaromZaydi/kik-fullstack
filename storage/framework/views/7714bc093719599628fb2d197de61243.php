<div class="card">
    <div class="card-header bg-warning text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-clipboard-check me-2"></i>Review Akhir
        </h5>
    </div>

    <div class="card-body">
        <?php
            $allValid = true;
            $requiredTabs = ['data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung'];
            $verifikasiStatus = [];

            foreach ($requiredTabs as $tab) {
                $verifikasi = $verifikasiData->where('tipe', $tab)->first();
                $status = $verifikasi->status ?? 'belum_divalidasi';

                $verifikasiStatus[$tab] = [
                    'status' => $status,
                    'data' => $verifikasi,
                ];

                if ($status !== 'valid') {
                    $allValid = false;
                }
            }
        ?>

        
        <?php if(!$allValid): ?>
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Data Belum Lengkap</h5>
                <p>Berikut status verifikasi setiap bagian:</p>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Bagian</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Keterangan</th>
                                <th>Tanggal Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $requiredTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $status = $verifikasiStatus[$tab]['status'];
                                    $data = $verifikasiStatus[$tab]['data'];
                                ?>
                                <tr>
                                    <td><strong><?php echo e(ucfirst(str_replace('_', ' ', $tab))); ?></strong></td>
                                    <td>
                                        <?php switch($status):
                                            case ('valid'): ?>
                                                <span class="badge bg-success">✓ Valid</span>
                                            <?php break; ?>

                                            <?php case ('tdk_valid'): ?>
                                                <span class="badge bg-danger">✗ Tidak Valid</span>
                                            <?php break; ?>

                                            <?php default: ?>
                                                <span class="badge bg-secondary">● Belum Divalidasi</span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td><?php echo e($data->catatan ?? '-'); ?></td>
                                    <td><?php echo e($data->keterangan ?? '-'); ?></td>
                                    <td><?php echo e($data?->tanggal_review?->format('d/m/Y H:i') ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center">
                <p class="text-muted mb-3">
                    Tidak dapat menyetujui organisasi karena ada data yang belum valid.
                </p>
                <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi'])); ?>"
                    class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Lanjutkan Verifikasi
                </a>
            </div>

            
        <?php else: ?>
            <div class="alert alert-success d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                <div>
                    <h5 class="mb-1">Semua Data Valid!</h5>
                    <p class="mb-0">Semua data pendaftaran sudah memenuhi kriteria.
                        Organisasi dapat disetujui dan mendapatkan Kartu Induk Kesenian.</p>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Detail Verifikasi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Bagian</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Tanggal Review</th>
                                    <th>Reviewer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $requiredTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $data = $verifikasiStatus[$tab]['data']; ?>
                                    <tr>
                                        <td><strong><?php echo e(ucfirst(str_replace('_', ' ', $tab))); ?></strong></td>
                                        <td><span class="badge bg-success">✓ Valid</span></td>
                                        <td><?php echo e($data->catatan ?? '-'); ?></td>
                                        <td><?php echo e($data?->tanggal_review?->format('d/m/Y H:i') ?? '-'); ?></td>
                                        <td><?php echo e($data?->reviewer?->name ?? 'System'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="<?php echo e(route('admin.verifikasi.approve', $organisasi->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Setujui Organisasi
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="<?php echo e(route('admin.verifikasi.reject', $organisasi->id)); ?>" method="POST"
                        onsubmit="return confirm('Yakin ingin menolak organisasi ini?')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-times-circle me-2"></i>Tolak Organisasi
                        </button>
                    </form>
                </div>
            </div>


            
            
            
            <?php if($organisasi->status === 'Allow'): ?>
                <div class="text-center mt-4">
                    
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#previewKartuModal"
                        data-kartu-url="<?php echo e(route('admin.verifikasi.kartu', $organisasi->id)); ?>">
                        <i class="fas fa-id-card me-2"></i> Tampilkan Kartu Induk
                    </button>
                </div>
            <?php endif; ?>


            
            
            
            <div class="modal fade" id="previewKartuModal" tabindex="-1" aria-labelledby="previewKartuModalLabel"
                aria-hidden="true">
                
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="previewKartuModalLabel">
                                <i class="fas fa-id-card me-2"></i>Preview Kartu Induk Kesenian
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        
                        <div class="modal-body text-center" style="background: #f5f5f5; min-height: 250px;">
                            <div id="previewKartuContent">
                                
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                    role="status">
                                    <span class="visually-hidden">Memuat...</span>
                                </div>
                                <p class="mt-3 text-muted">Sedang men-generate kartu...</p>
                            </div>
                        </div>

                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Tutup
                            </button>
                            <a href="#" class="btn btn-success disabled" id="btnDownloadKartu"
                                download="kartu_induk_<?php echo e($organisasi->nomor_induk ?? $organisasi->id); ?>.png">
                                <i class="fas fa-download me-2"></i>Download Gambar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?> 

        
        <div class="text-start mt-4">
            <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_pendukung'])); ?>"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>





<?php $__env->startPush('scripts'); ?>
    <script>
        // Pastikan DOM sudah dimuat
        document.addEventListener('DOMContentLoaded', function() {
            var previewModalEl = document.getElementById('previewKartuModal');

            // Jika modal tidak ada di halaman ini, hentikan script
            if (!previewModalEl) {
                return;
            }

            var modal = new bootstrap.Modal(previewModalEl);
            var contentArea = document.getElementById('previewKartuContent');
            var downloadButton = document.getElementById('btnDownloadKartu');

            // Simpan HTML spinner awal
            var spinnerHtml = contentArea.innerHTML;

            // 1. Saat modal AKAN DITAMPILKAN
            previewModalEl.addEventListener('show.bs.modal', function(event) {
                // Dapatkan tombol yang memicu modal
                var button = event.relatedTarget;
                // Dapatkan URL gambar dari atribut data-
                var kartuUrl = button.getAttribute('data-kartu-url');

                if (!kartuUrl) {
                    contentArea.innerHTML = '<p class="text-danger">Gagal mendapatkan URL kartu.</p>';
                    return;
                }

                // Buat URL untuk download
                var downloadUrl = kartuUrl + '?download=true';

                // Buat elemen gambar baru
                var img = new Image();
                img.className = 'img-fluid'; // Agar responsif di dalam modal
                img.style.borderRadius = '12px'; // Estetika
                img.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';

                // 2. Saat gambar BERHASIL dimuat
                img.onload = function() {
                    contentArea.innerHTML = ''; // Hapus spinner
                    contentArea.appendChild(img); // Tampilkan gambar

                    // Atur link download dan aktifkan tombolnya
                    downloadButton.setAttribute('href', downloadUrl);
                    downloadButton.classList.remove('disabled');
                };

                // 3. Saat gambar GAGAL dimuat
                img.onerror = function() {
                    contentArea.innerHTML =
                        '<p class="text-danger">Gagal memuat preview kartu. Silakan coba lagi.</p>';
                };

                // 4. Mulai proses muat gambar (ini akan memanggil URL di controller)
                img.src = kartuUrl;
            });

            // 5. Saat modal DITUTUP
            previewModalEl.addEventListener('hidden.bs.modal', function() {
                // Kembalikan konten ke spinner awal
                contentArea.innerHTML = spinnerHtml;

                // Nonaktifkan dan reset tombol download
                downloadButton.setAttribute('href', '#');
                downloadButton.classList.add('disabled');
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/review.blade.php ENDPATH**/ ?>