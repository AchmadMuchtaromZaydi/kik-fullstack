<?php if(auth()->guard()->check()): ?>
    <div class="offcanvas offcanvas-lg offcanvas-start sidebar p-0" tabindex="-1" id="sidebarMenu"
        aria-labelledby="sidebarMenuLabel">

        <div class="offcanvas-header d-lg-none sidebar-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">
                <i class="fas fa-theater-masks me-2"></i>KIK System
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">
            <nav class="nav flex-column p-3">

                
                <?php if(Auth::user()->role === 'admin'): ?>
                    <a class="nav-link <?php echo e(Request::is('admin/dashboard') ? 'active' : ''); ?>"
                        href="<?php echo e(route('admin.dashboard')); ?>">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>

                    <a class="nav-link <?php echo e(Request::is('admin/kesenian*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('admin.kesenian.index')); ?>">
                        <i class="fas fa-music me-2"></i>Data Kesenian
                    </a>

                    <a class="nav-link <?php echo e(Request::is('admin/jenis-kesenian*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('admin.jenis-kesenian')); ?>">
                        <i class="fas fa-list me-2"></i>Data Jenis Kesenian
                    </a>

                    <a class="nav-link <?php echo e(Request::is('admin/users*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users')); ?>">
                        <i class="fas fa-users me-2"></i>Kelola User
                    </a>
                <?php endif; ?>

                
                <?php if(Auth::user()->role === 'user-kik'): ?>
                    <a class="nav-link <?php echo e(Request::is('user-kik/dashboard') ? 'active' : ''); ?>"
                        href="<?php echo e(route('dashboard')); ?>">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>

                    
                <?php endif; ?>

                

                <hr class="mt-2 mb-2">

                <form action="<?php echo e(route('auth.logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="nav-link">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>

            </nav>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\New Code\kik-fullstack\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>