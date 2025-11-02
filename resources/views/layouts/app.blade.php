{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KIK - Sistem Kesenian')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Mencegah scroll horizontal di mobile */
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }

        /* * CSS UTAMA UNTUK LAYOUT BARU */
        .main-content {
            padding-top: 70px;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 992px) {
            .main-content {
                margin-left: 280px;
            }

            .sidebar.offcanvas-lg {
                width: 280px;
                transform: none !important;
                visibility: visible !important;
                position: fixed;
                top: 70px;
                bottom: 0;
                z-index: 1020;
            }
        }

        /* --- Mempercantik UI Sidebar (TEMA TERANG) --- */
        .sidebar {
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
        }

        .sidebar .nav-link {
            color: #34495e;
            padding: 12px 16px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .sidebar .nav-link .fas {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        /* Hover effect */
        .sidebar .nav-link:hover {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }

        /* Link aktif */
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #0d6efd;
            font-weight: 600;
        }

        /* Header sidebar mobile */
        .sidebar .sidebar-header {
            border-bottom: 1px solid #dee2e6;
        }

        /* * STYLE UNTUK CARD & COMPONENT LAIN */
        .stat-card {
            border-radius: 10px;
            transition: transform 0.3s;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body>

    {{-- Memuat Header --}}
    @include('layouts.partials.header')

    {{-- Memuat Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Konten Utama --}}
    <div class="main-content d-flex flex-column min-vh-100">
        <main class="p-3 p-md-4 flex-grow-1">
            @yield('content')
        </main>

        {{-- Memuat Footer --}}
        @include('layouts.partials.footer')
    </div>

    {{-- Modal hanya untuk user yang login --}}
    @auth
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
    @endauth

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @auth
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
    @endauth

    @stack('scripts')
</body>

</html>
