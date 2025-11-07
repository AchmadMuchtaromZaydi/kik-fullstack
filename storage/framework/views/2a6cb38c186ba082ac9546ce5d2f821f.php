

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard User</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Selamat Datang, <?php echo e(Auth::user()->name); ?>!</h5>
                        <p class="card-text">Anda login sebagai <strong>User KIK</strong>.</p>

                        <!-- Konten dashboard lainnya -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\main\kik-fullstack\resources\views/dashboard.blade.php ENDPATH**/ ?>