
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
                    <i class="fas fa-list me-2"></i>Jenis Kesenian
                </a>

                <a class="nav-link <?php echo e(Request::is('admin/users*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users')); ?>">
                    <i class="fas fa-users me-2"></i>Kelola User
                </a>


                <a class="nav-link <?php echo e(Request::is('admin/anggota*') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.anggota.index')); ?>">
                    <i class="fas fa-user-friends me-2"></i>Anggota Kesenian
                </a>


                <a class="nav-link <?php echo e(Request::is('admin/laporan*') ? 'active' : ''); ?>"
                    href="<?php echo e(route('admin.laporan')); ?>">
                    <i class="fas fa-chart-bar me-2"></i>Laporan
                </a>
            </nav>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>