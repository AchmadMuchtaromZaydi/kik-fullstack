


<?php $__env->startSection('title', 'Data Jenis Kesenian'); ?>
<?php $__env->startSection('page-title', 'Data Jenis Kesenian'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Data Jenis Kesenian</h3>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalJenisKesenian" onclick="resetModal()">
                    <i class="fas fa-plus me-2"></i>Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td width="1%">No</td>
                            <td>Jenis Kesenian</td>
                            <td>Sub Kesenian</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dataJenisKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td>
                                <strong><?php echo e($item->nama); ?></strong>
                            </td>
                            <td>-- PARENT --</td>
                            <td>
                                <button class="btn text-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalJenisKesenian"
                                        onclick="editJenisKesenian(<?php echo e($item->id); ?>, '<?php echo e($item->nama); ?>')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?php echo e(route('admin.jenis-kesenian.destroy', $item->id)); ?>"
                                      method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="btn text-danger"
                                            <?php echo e($item->sub->count() > 0 ? 'disabled' : ''); ?>

                                            onclick="return confirm('Hapus jenis kesenian <?php echo e($item->nama); ?>?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <?php $__currentLoopData = $item->sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><?php echo e($subItem->nama); ?></td>
                            <td>
                                <button class="btn text-info mr-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalJenisKesenian"
                                        onclick="editSubJenisKesenian(<?php echo e($subItem->id); ?>, '<?php echo e($subItem->nama); ?>', <?php echo e($item->id); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?php echo e(route('admin.jenis-kesenian.destroy', $subItem->id)); ?>"
                                      method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit"
                                            class="btn text-danger"
                                            onclick="return confirm('Hapus sub jenis kesenian <?php echo e($subItem->nama); ?>?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data jenis kesenian</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalJenisKesenian" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="jenisKesenianForm" method="POST">
                <?php echo csrf_field(); ?>
                <div id="formMethod"></div>

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Jenis Kesenian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group mb-3">
                                <label for="nama" class="form-label">
                                    Jenis Kesenian <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nama" name="nama" required
                                       placeholder="Masukkan nama jenis kesenian">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="parent" class="form-label">
                                    Sub Kesenian
                                </label>
                                <select class="form-control" id="parent" name="parent">
                                    <option value="">Parent</option>
                                    <?php $__currentLoopData = $parentJenisKesenian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($parent->id); ?>"><?php echo e($parent->nama); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-muted">Kosongkan jika ini adalah jenis utama</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-info">SAVE</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function resetModal() {
        document.getElementById('jenisKesenianForm').reset();
        document.getElementById('jenisKesenianForm').action = "<?php echo e(route('admin.jenis-kesenian.store')); ?>";
        document.getElementById('formMethod').innerHTML = '';
        document.getElementById('modalTitle').textContent = 'Tambah Jenis Kesenian';
        document.getElementById('parent').value = '';
    }

    function editJenisKesenian(id, nama) {
        resetModal();
        document.getElementById('jenisKesenianForm').action = "<?php echo e(url('admin/jenis-kesenian')); ?>/" + id;
        document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').textContent = 'Edit Jenis Kesenian';
        document.getElementById('nama').value = nama;
        document.getElementById('parent').value = ''; // Parent jenis utama
    }

    function editSubJenisKesenian(id, nama, parentId) {
        resetModal();
        document.getElementById('jenisKesenianForm').action = "<?php echo e(url('admin/jenis-kesenian')); ?>/" + id;
        document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').textContent = 'Edit Sub Jenis Kesenian';
        document.getElementById('nama').value = nama;
        document.getElementById('parent').value = parentId;
    }

    // Reset modal ketika ditutup
    document.getElementById('modalJenisKesenian').addEventListener('hidden.bs.modal', function () {
        resetModal();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New Code\kik-fullstack\resources\views/admin/jenis-kesenian/index.blade.php ENDPATH**/ ?>