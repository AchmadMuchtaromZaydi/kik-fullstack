
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
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $organisasi->inventaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($item->nama); ?></td>
                            <td><?php echo e($item->jumlah); ?></td>
                            <td><?php echo e($item->pembelian_th ?? '-'); ?></td>
                            <td>
                                <span
                                    class="badge bg-<?php echo e($item->kondisi == 'Baik' ? 'success' : ($item->kondisi == 'Rusak' ? 'danger' : 'warning')); ?>">
                                    <?php echo e($item->kondisi ?? 'Tidak Diketahui'); ?>

                                </span>
                            </td>
                            <td><?php echo e($item->keterangan ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data inventaris</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <strong>Total Barang:</strong> <?php echo e($organisasi->inventaris->count()); ?> item
            <?php if($organisasi->inventaris->count() > 0): ?>
                | <strong>Total Jumlah:</strong> <?php echo e($organisasi->inventaris->sum('jumlah')); ?> unit
            <?php endif; ?>
        </div>

        <hr>

        <form action="<?php echo e(route('admin.verifikasi.store', $organisasi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tipe" value="data_inventaris">

            <h5>Verifikasi Data Inventaris</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_inventaris')->first()->status ?? '') == 'valid' ? 'selected' : ''); ?>>
                                Valid
                            </option>
                            <option value="tdk_valid"
                                <?php echo e(($verifikasiData->where('tipe', 'data_inventaris')->first()->status ?? '') == 'tdk_valid' ? 'selected' : ''); ?>>
                                Tidak Valid
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Internal</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan untuk internal admin"><?php echo e($verifikasiData->where('tipe', 'data_inventaris')->first()->catatan ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan untuk Pendaftar</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Keterangan yang akan dilihat oleh pendaftar"><?php echo e($verifikasiData->where('tipe', 'data_inventaris')->first()->keterangan ?? ''); ?></textarea>
                <small class="text-muted">
                    Contoh: "Data inventaris sudah lengkap dan valid" atau "Perlu melengkapi data inventaris"
                </small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_anggota'])); ?>"
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
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/tabs/data_inventaris.blade.php ENDPATH**/ ?>