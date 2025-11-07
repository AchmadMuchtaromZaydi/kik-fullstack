
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'KIK - Sistem Kesenian'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display-swap" rel="stylesheet">

    <style>
        /* * CSS UTAMA */
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
            /* Background area konten */
            font-family: 'Inter', sans-serif;
        }

        /* * HEADER (DIUBAH) */
        .admin-header {
            height: 70px;
            z-index: 1030;
            background-color: #3b85ac;
            /* BARU: Warna kustom Anda */
            border-bottom: 1px solid #316f92;
            /* BARU: Versi gelap dari warna Anda */
        }

        /* Teks judul di header */
        .admin-header .admin-title strong {
            color: #ffffff;
        }

        .admin-header .admin-title small {
            color: rgba(255, 255, 255, 0.85);
            /* Putih transparan */
        }

        .admin-header .admin-title i {
            color: #ffffff;
            /* Putih */
        }

        /* Dropdown user di header */
        .admin-header .dropdown-toggle {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .admin-header .dropdown-toggle:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.2);
        }


        /* * SIDEBAR */
        .sidebar {
            width: 280px;
        }

        /* Ini menargetkan sidebar di tampilan MOBILE (DIUBAH) */
        .offcanvas.sidebar {
            background-color: #3b85ac;
            /* BARU: Warna kustom Anda */
            border-right: none;
            /* Mobile tidak perlu border */
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: 280px;
            }

            /* Ini menargetkan sidebar di tampilan DESKTOP (DIUBAH) */
            .sidebar.offcanvas-lg {
                background-color: #3b85ac !important;
                /* BARU: Warna kustom Anda */
                border-right: 1px solid #316f92;
                /* BARU: Versi gelap dari warna Anda */
                width: 280px;
                transform: none !important;
                visibility: visible !important;
                position: fixed;
                top: 70px;
                bottom: 0;
                z-index: 1020;
            }
        }

        /* Header sidebar mobile */
        .sidebar .sidebar-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            /* Garis pemisah */
            height: 70px;
            display: flex;
            align-items: center;
        }

        .sidebar .sidebar-header .offcanvas-title {
            color: #ffffff;
        }

        .sidebar .sidebar-header .btn-close {
            background-color: #ffffff;
        }


        /* Link Navigasi Sidebar */
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            /* Teks putih transparan */
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link .fas {
            width: 24px;
            margin-right: 12px;
            text-align: center;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.6);
            /* Icon putih lebih transparan */
        }

        /* Hover effect (DIUBAH) */
        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: #316f92;
            /* BARU: Versi gelap dari warna Anda */
        }

        .sidebar .nav-link:hover .fas {
            color: #ffffff;
            /* Icon jadi putih */
        }

        /* Link aktif (DIUBAH) */
        .sidebar .nav-link.active {
            color: #3b85ac;
            /* BARU: Teks warna kustom Anda */
            background-color: #ffffff;
            /* Background putih */
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link.active .fas {
            color: #3b85ac;
            /* BARU: Icon warna kustom Anda */
        }

        /* * KONTEN UTAMA */
        .main-content {
            padding-top: 70px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* * STYLE CARD */
        .stat-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* * FOOTER (DIUBAH TOTAL) */
        .admin-footer {
            background-color: #ffffff;
            /* Sesuai permintaan: putih */
            border-radius: 10px;
            /* Bikin "box" rounded */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
            /* Shadow seperti card */
            padding: 1rem 1.5rem;
            /* Padding internal */
            border: 1px solid #dee2e6;
            /* Border tipis */
        }

        .admin-footer small {
            color: #6c757d;
            /* Teks abu-abu standar */
        }
    </style>
</head>

<body>

    
    <?php echo $__env->make('layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('layouts.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="main-content d-flex flex-column min-vh-100">
        
        <main class="p-3 p-md-4 flex-grow-1">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        
        <div class="px-3 px-md-4 pb-3 pb-md-4">
            <?php echo $__env->make('layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    
    <?php if(auth()->guard()->check()): ?>
        <div class="modal fade" id="statModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Detail Statistik</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php if(auth()->guard()->check()): ?>
        <script>
            function loadStatDetail(type) {
                $('#modalTitle').text('Memuat...');
                $('#modalContent').html('<div class="text-center"><div class="spinner-border"></div></div>');

                $.ajax({
                    url: '/admin/dashboard/stats/' + type,
                    type: 'GET',
                    success: function(response) {
                        $('#modalTitle').text(response.title);
                        $('#modalContent').html(response.content);
                    },
                    error: function() {
                        $('#modalContent').html('<div class="alert alert-danger">Gagal memuat data</div>');
                    }
                });

                var myModal = new bootstrap.Modal(document.getElementById('statModal'));
                myModal.show();
            }
        </script>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH D:\main\kik-fullstack\resources\views/layouts/app.blade.php ENDPATH**/ ?>