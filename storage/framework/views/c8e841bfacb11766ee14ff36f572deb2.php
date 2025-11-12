
<style>
    /* ====== THEME ====== */
    :root {
        --bs-theme-primary: #299160FF;
        --bs-theme-light: #f8fdf9;
    }

    .bg-pink {
        background-color: #e83e8c !important;
        color: white !important;
    }

    .card {
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    /* ====== TABLE STYLE ====== */
    .table {
        border-radius: 0.6rem;
        overflow: hidden;
        background: #fff;
    }

    .table thead {
        background: linear-gradient(90deg, #299160, #34c38f);
        color: #fff;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }


    .table th {
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: var(--bs-theme-light);
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    /* ====== ORGANIZATION CHART ====== */
    .org-tree {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 3rem;
        position: relative;
    }

    .org-branch {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        margin-top: 2rem;
        width: 100%;
    }

    .org-node {
        background: #fff;
        border: 2px solid var(--bs-theme-primary);
        border-radius: 0.75rem;
        padding: 1rem 1.5rem;
        text-align: center;
        min-width: 180px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    .org-node.bg-primary {
        background: var(--bs-theme-primary) !important;
        color: #fff !important;
        border-color: var(--bs-theme-primary);
    }

    /* Garis dari ketua ke bawah */
    .org-node.parent::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        width: 2px;
        height: 30px;
        background-color: var(--bs-theme-primary);
        transform: translateX(-50%);
        z-index: 0;
    }

    /* Garis horizontal antar anak */
    .org-branch::before {
        content: '';
        position: absolute;
        top: -15px;
        left: 50%;
        width: 200px;
        height: 2px;
        background-color: var(--bs-theme-primary);
        transform: translateX(-50%);
        z-index: 0;
    }

    .org-branch .org-node {
        flex: 0 1 250px;
        margin: 0 2rem;
    }

    .org-role {
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.4px;
        margin-bottom: 0.3rem;
        opacity: 0.85;
    }

    .org-role.text-success {
        color: #198754 !important;
    }

    .org-role.text-warning {
        color: #d39e00 !important;
    }
</style>

<div class="card border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
        <h5 class="card-title mb-0">
            <i class="fas fa-boxes me-2"></i>Data Anggota
        </h5>
        <span class="badge bg-light text-dark fs-6 rounded-pill">
            <?php echo e($anggota_terurut->count()); ?> Orang
        </span>
    </div>

    <div class="card-body p-4">
        
        <?php if($anggota_terurut->count() == 0): ?>
            <div class="alert alert-warning shadow-sm">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Informasi</h6>
                <p>Organisasi <strong><?php echo e($organisasi->nama); ?></strong> belum memiliki anggota.</p>
            </div>
        <?php endif; ?>

        
        <div class="table-responsive mb-5">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th class="text-center" width="50">No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th class="text-center" width="80">L/P</th>
                        <th class="text-center" width="80">Umur</th>
                        <th>Pekerjaan</th>
                        <th>Jabatan</th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $anggota_terurut; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td><?php echo e($anggota->nik ?? '-'); ?></td>
                            <td><strong><?php echo e($anggota->nama); ?></strong></td>
                            <td class="text-center">
                                <?php if($anggota->jenis_kelamin == 'L'): ?>
                                    <span class="badge bg-info">L</span>
                                <?php elseif($anggota->jenis_kelamin == 'P'): ?>
                                    <span class="badge bg-pink">P</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($anggota->umur): ?>
                                    <span class="badge bg-secondary"><?php echo e($anggota->umur); ?> th</span>
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
                                        'Bendahara' => 'warning text-dark',
                                        'Wakil Ketua' => 'info text-dark',
                                        'Anggota' => 'secondary',
                                    ];
                                    $color = $jabatanColors[$anggota->jabatan] ?? 'dark';
                                ?>
                                <span class="badge bg-<?php echo e($color); ?> px-3 py-2">
                                    <?php echo e($anggota->jabatan); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($anggota->whatsapp || $anggota->telepon): ?>
                                    <div>
                                        <?php if($anggota->whatsapp): ?>
                                            <small><i
                                                    class="fab fa-whatsapp text-success me-1"></i><?php echo e($anggota->whatsapp); ?></small><br>
                                        <?php endif; ?>
                                        <?php if($anggota->telepon && $anggota->telepon != $anggota->whatsapp): ?>
                                            <small><i
                                                    class="fas fa-phone text-muted me-1"></i><?php echo e($anggota->telepon); ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-2x mb-2"></i><br>
                                <strong>Tidak ada data anggota</strong><br>
                                <small>Belum ada anggota yang terdaftar</small>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php
            $ketua = $anggota_terurut->where('jabatan', 'Ketua')->first();
            $sekretaris = $anggota_terurut->where('jabatan', 'Sekretaris')->first();
            $bendahara = $anggota_terurut->where('jabatan', 'Bendahara')->first();
        ?>

        <h5 class="text-success text-center mb-4">
            <i class="fas fa-sitemap me-2"></i>Struktur Kepengurusan Inti
        </h5>

        <div class="org-tree">
            <div class="org-node parent bg-primary text-white">
                <div class="org-role"><i class="fas fa-crown me-1"></i>Ketua</div>
                <?php echo e($ketua->nama ?? 'Belum Ada'); ?>

            </div>

            <div class="org-branch">
                <div class="org-node">
                    <div class="org-role text-success"><i class="fas fa-pen-nib me-1"></i>Sekretaris</div>
                    <?php echo e($sekretaris->nama ?? 'Belum Ada'); ?>

                </div>
                <div class="org-node">
                    <div class="org-role text-warning"><i class="fas fa-coins me-1"></i>Bendahara</div>
                    <?php echo e($bendahara->nama ?? 'Opsional'); ?>

                </div>
            </div>
        </div>

        <div class="mt-4 text-center text-muted">
            <strong>Total Anggota:</strong> <?php echo e($anggota_terurut->count()); ?> orang
        </div>

        <hr class="my-5">

        
        <form action="<?php echo e(route('admin.verifikasi.store', $organisasi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipe" value="data_anggota">

            <h5 class="text-primary mb-4">
                <i class="fas fa-clipboard-check me-2"></i>Verifikasi Data Anggota
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status Verifikasi</label>
                    <select name="status" class="form-select" required>
                        <option value="">Pilih Status</option>
                        <option value="valid"
                            <?php echo e(($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'valid' ? 'selected' : ''); ?>>
                            ✅ Valid - Data anggota lengkap
                        </option>
                        <option value="tdk_valid"
                            <?php echo e(($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'tdk_valid' ? 'selected' : ''); ?>>
                            ❌ Tidak Valid - Ada kekurangan
                        </option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Keputusan Berdasarkan</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" <?php echo e($ketua ? 'checked' : ''); ?> disabled>
                        <label class="form-check-label">Struktur Ketua <?php echo e($ketua ? '✓' : '✗'); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" <?php echo e($sekretaris ? 'checked' : ''); ?> disabled>
                        <label class="form-check-label">Struktur Sekretaris <?php echo e($sekretaris ? '✓' : '✗'); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" <?php echo e($bendahara ? 'checked' : ''); ?> disabled>
                        <label class="form-check-label">Struktur Bendahara <?php echo e($bendahara ? '✓' : '✗'); ?></label>
                    </div>
                </div>
            </div>

            <div class="mb-3 mt-3">
                <label class="form-label fw-bold"><i class="fas fa-sticky-note me-1"></i>Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2"><?php echo e($verifikasiData->where('tipe', 'data_anggota')->first()->catatan ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold"><i class="fas fa-comment-dots me-1"></i>Keterangan untuk
                    Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"><?php echo e($verifikasiData->where('tipe', 'data_anggota')->first()->keterangan ?? ''); ?></textarea>
                <small class="text-muted">Contoh: "Data anggota sudah lengkap dan valid"</small>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi'])); ?>"
                    class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>

                <?php if($anggota_terurut->count() > 0): ?>
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-save me-2"></i>Simpan & Lanjutkan
                        <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-danger" disabled>
                        <i class="fas fa-exclamation-triangle me-2"></i>Tidak bisa verifikasi
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/data_anggota.blade.php ENDPATH**/ ?>