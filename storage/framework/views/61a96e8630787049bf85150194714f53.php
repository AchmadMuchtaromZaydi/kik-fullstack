
<header class="admin-header shadow-sm fixed-top">
    <div class="container-fluid d-flex align-items-center h-100">

        
        <?php if(auth()->guard()->check()): ?>
            <button class="btn btn-outline-primary me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <i class="fas fa-bars"></i>
            </button>
        <?php endif; ?>

        
        <a href="<?php echo e(auth()->check() ? route('admin.dashboard') : route('auth.login')); ?>"
            class="text-decoration-none d-none d-lg-block me-auto admin-title">
            <h5 class="mb-0">
                <i class="fas fa-theater-masks me-2"></i>
                <strong>KIK System</strong>
                <?php if(auth()->guard()->check()): ?>
                    <small>Admin Panel</small>
                <?php else: ?>
                    <small>Sistem Informasi Kesenian</small>
                <?php endif; ?>
            </h5>
        </a>

        
        <h4 class="mb-0 text-white me-auto d-lg-none">
            <?php echo $__env->yieldContent('page-title', auth()->check() ? 'Dashboard' : 'Home'); ?>
        </h4>

        
        <?php if(auth()->guard()->check()): ?>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-2"></i><?php echo e(Auth::user()->name); ?>

                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="<?php echo e(route('auth.logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        <?php else: ?>
            
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('auth.login')); ?>" class="btn btn-outline-light">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="<?php echo e(route('auth.register')); ?>" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            </div>
        <?php endif; ?>
    </div>
</header>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/layouts/partials/header.blade.php ENDPATH**/ ?>