
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>Data Anggota (<?php echo e($organisasi->anggota->count()); ?>)
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>L/P</th>
                        <th>Umur</th>
                        <th>Pekerjaan</th>
                        <th>Jabatan</th>
                        <th>Kontak</th>
                        <th>Status Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $organisasi->anggota; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $anggota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($anggota->nik ?? '-'); ?></td>
                            <td><?php echo e($anggota->nama); ?></td>
                            <td><?php echo e($anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                            <td>
                                <?php if($anggota->tanggal_lahir): ?>
                                    <?php echo e(\Carbon\Carbon::parse($anggota->tanggal_lahir)->age); ?> th
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($anggota->pekerjaan ?? '-'); ?></td>
                            <td>
                                <span
                                    class="badge bg-<?php echo e($anggota->jabatan == 'Ketua' ? 'primary' : ($anggota->jabatan == 'Sekretaris' ? 'success' : 'secondary')); ?>">
                                    <?php echo e($anggota->jabatan); ?>

                                </span>
                            </td>
                            <td><?php echo e($anggota->telepon ?? ($anggota->whatsapp ?? '-')); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($anggota->validasi ? 'success' : 'warning'); ?>">
                                    <?php echo e($anggota->validasi ? 'Terverifikasi' : 'Belum Diverifikasi'); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($organisasi->anggota->count() == 0): ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data anggota</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php
                $ketua = $organisasi->anggota->where('jabatan', 'Ketua')->first();
                $sekretaris = $organisasi->anggota->where('jabatan', 'Sekretaris')->first();
            ?>
            <strong>Struktur Kepengurusan:</strong>
            <?php if($ketua): ?>
                <span class="badge bg-primary">Ketua: <?php echo e($ketua->nama); ?></span>
            <?php else: ?>
                <span class="badge bg-danger">Ketua: Belum Ada</span>
            <?php endif; ?>

            <?php if($sekretaris): ?>
                <span class="badge bg-success ms-2">Sekretaris: <?php echo e($sekretaris->nama); ?></span>
            <?php else: ?>
                <span class="badge bg-danger ms-2">Sekretaris: Belum Ada</span>
            <?php endif; ?>
        </div>

        <hr>

        <form action="<?php echo e(route('admin.verifikasi.store', $organisasi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipe" value="data_anggota">

            <h5>Verifikasi Data Anggota</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'valid' ? 'selected' : ''); ?>>
                                Valid</option>
                            <option value="tdk_valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_anggota')->first()->status ?? '') == 'tdk_valid' ? 'selected' : ''); ?>>
                                Tidak Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk internal admin"><?php echo e($verifikasiData->where('tipe', 'data_anggota')->first()->catatan ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar"><?php echo e($verifikasiData->where('tipe', 'data_anggota')->first()->keterangan ?? ''); ?></textarea>
                <small class="text-muted">Contoh: "Data anggota sudah lengkap" atau "Perlu melengkapi data
                    sekretaris"</small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi'])); ?>"
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
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/data_anggota.blade.php ENDPATH**/ ?>