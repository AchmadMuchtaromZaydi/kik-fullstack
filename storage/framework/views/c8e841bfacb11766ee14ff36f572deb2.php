
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>Data Anggota
            <span class="badge bg-light text-dark">(<?php echo e($organisasi->anggota->count()); ?>)</span>
        </h5>
    </div>
    <div class="card-body">

        
        <?php if($organisasi->anggota->count() == 0): ?>
            <div class="alert alert-warning">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Debug Information</h6>
                <p><strong>Organisasi ID:</strong> <?php echo e($organisasi->id); ?></p>
                <p><strong>Nama Organisasi:</strong> <?php echo e($organisasi->nama); ?></p>
                <p><strong>Jumlah Anggota di Database:</strong>
                    <?php
                        $countFromDB = \App\Models\Anggota::where('organisasi_id', $organisasi->id)->count();
                    ?>
                    <?php echo e($countFromDB); ?>

                </p>
                <p><strong>Relasi Loaded:</strong> <?php echo e($organisasi->relationLoaded('anggota') ? 'Ya' : 'Tidak'); ?></p>
            </div>
        <?php endif; ?>

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
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $organisasi->anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td>
                                <?php if($anggota->nik): ?>
                                    <code><?php echo e($anggota->nik); ?></code>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($anggota->nama); ?></strong>
                                <?php if($anggota->jabatan == 'Ketua'): ?>
                                    <span class="badge bg-primary ms-1">Ketua</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($anggota->jenis_kelamin == 'L'): ?>
                                    <span class="badge bg-info">L</span>
                                <?php else: ?>
                                    <span class="badge bg-pink">P</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($anggota->tanggal_lahir): ?>
                                    <span class="badge bg-secondary">
                                        <?php echo e(\Carbon\Carbon::parse($anggota->tanggal_lahir)->age); ?> th
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($anggota->pekerjaan ?? '-'); ?></td>
                            <td>
                                <?php
                                    $jabatanColors = [
                                        'Ketua' => 'primary',
                                        'Sekretaris' => 'success',
                                        'Bendahara' => 'warning',
                                        'Wakil Ketua' => 'info',
                                        'Anggota' => 'secondary',
                                    ];
                                    $color = $jabatanColors[$anggota->jabatan] ?? 'dark';
                                ?>
                                <span class="badge bg-<?php echo e($color); ?>">
                                    <?php echo e($anggota->jabatan); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($anggota->telepon || $anggota->whatsapp): ?>
                                    <div>
                                        <?php if($anggota->telepon): ?>
                                            <small>Tel: <?php echo e($anggota->telepon); ?></small><br>
                                        <?php endif; ?>
                                        <?php if($anggota->whatsapp): ?>
                                            <small>WA: <?php echo e($anggota->whatsapp); ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="mb-3"><i class="fas fa-sitemap me-2"></i>Struktur Kepengurusan</h6>
            <?php
                $ketua = $organisasi->anggota->where('jabatan', 'Ketua')->first();
                $sekretaris = $organisasi->anggota->where('jabatan', 'Sekretaris')->first();
                $bendahara = $organisasi->anggota->where('jabatan', 'Bendahara')->first();
            ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2">Ketua</span>
                        <?php if($ketua): ?>
                            <span><?php echo e($ketua->nama); ?></span>
                        <?php else: ?>
                            <span class="text-danger">Belum Ada</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2">Sekretaris</span>
                        <?php if($sekretaris): ?>
                            <span><?php echo e($sekretaris->nama); ?></span>
                        <?php else: ?>
                            <span class="text-danger">Belum Ada</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-2">Bendahara</span>
                        <?php if($bendahara): ?>
                            <span><?php echo e($bendahara->nama); ?></span>
                        <?php else: ?>
                            <span class="text-muted">Opsional</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="mt-3 pt-3 border-top">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Total Anggota:</strong> <?php echo e($organisasi->anggota->count()); ?> orang
                    </div>
                </div>
            </div>
        </div>

        <hr>

        
        <form action="<?php echo e(route('admin.verifikasi.store', $organisasi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipe" value="data_anggota">

            <h5><i class="fas fa-clipboard-check me-2"></i>Verifikasi Data Anggota</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status Verifikasi</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'valid' ? 'selected' : ''); ?>>
                                ✅ Valid - Data anggota lengkap dan sesuai
                            </option>
                            <option value="tdk_valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'tdk_valid' ? 'selected' : ''); ?>>
                                ❌ Tidak Valid - Ada masalah dengan data anggota
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Keputusan Berdasarkan</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                <?php echo e($ketua ? 'checked' : ''); ?> disabled>
                            <label class="form-check-label">
                                Struktur ketua <?php echo e($ketua ? '✓' : '✗'); ?>

                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                <?php echo e($sekretaris ? 'checked' : ''); ?> disabled>
                            <label class="form-check-label">
                                Struktur sekretaris <?php echo e($sekretaris ? '✓' : '✗'); ?>

                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-sticky-note me-1"></i>Catatan Internal (Hanya untuk admin)
                </label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan internal untuk tim verifikasi..."><?php echo e($verifikasiData->where('tipe', 'data_anggota')->first()->catatan ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-comment-dots me-1"></i>Keterangan untuk Pendaftar
                </label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar organisasi..."><?php echo e($verifikasiData->where('tipe', 'data_anggota')->first()->keterangan ?? ''); ?></textarea>
                <small class="text-muted">
                    Contoh: "Data anggota sudah lengkap dan valid" atau "Perlu melengkapi data sekretaris dan menambah
                    jumlah anggota"
                </small>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi'])); ?>"
                    class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Data Organisasi
                </a>

                <div>
                    <?php if($organisasi->anggota->count() > 0): ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan & Lanjutkan
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-danger" disabled>
                            <i class="fas fa-exclamation-triangle me-2"></i>Tidak bisa verifikasi - Tidak ada anggota
                        </button>
                    <?php endif; ?>
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
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/data_anggota.blade.php ENDPATH**/ ?>