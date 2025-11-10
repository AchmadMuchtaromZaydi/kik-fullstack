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

                    <a class="nav-link <?php echo e(Request::is('user-kik/organisasi*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('user.organisasi.index')); ?>">
                        <i class="fas fa-building me-2"></i>Data Organisasi
                    </a>

                    <a class="nav-link <?php echo e(Request::is('user-kik/anggota*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('user.anggota.index')); ?>">
                        <i class="fas fa-users me-2"></i>Data Anggota
                    </a>

                    <a class="nav-link <?php echo e(Request::is('user-kik/inventaris*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('user.inventaris.index')); ?>">
                        <i class="fas fa-boxes me-2"></i>Inventaris Barang
                    </a>

                    <a class="nav-link <?php echo e(Request::is('user-kik/pendukung*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('user.pendukung.index')); ?>">
                        <i class="fas fa-folder-open me-2"></i>Data Pendukung
                    </a>

                    <a class="nav-link <?php echo e(Request::is('user-kik/validasi*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('user.validasi.index')); ?>">
                        <i class="fas fa-check-circle me-2"></i>Verifikasi Data
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
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>