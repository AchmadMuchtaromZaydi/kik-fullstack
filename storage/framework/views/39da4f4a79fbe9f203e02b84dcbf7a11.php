
<?php $__env->startSection('title', 'Tambah Inventaris'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-box me-2"></i>Tambah Data Inventaris</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('user.inventaris.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tahun Pembelian</label>
                    <input type="number" name="pembelian_th" class="form-control" min="1900" max="<?php echo e(date('Y')); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi" class="form-select" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baru">Baru</option>
                        <option value="Bekas">Bekas</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2"></textarea>
                </div>

                <div class="text-end">
                    <a href="<?php echo e(route('user.inventaris.index')); ?>" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Project\kik-fullstack-main (2)\Project\kik-fullstack\resources\views/user/inventaris/create.blade.php ENDPATH**/ ?>