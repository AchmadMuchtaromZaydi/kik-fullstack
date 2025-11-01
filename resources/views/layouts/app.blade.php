<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Induk Kesenian</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --blue: #1386b0;
            --white: #ffffff;
        }

        body {
            font-family: 'Work Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Layout untuk user yang login */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar di sebelah kiri */
        .sidebar-container {
            width: 250px;
            min-height: 100vh;
            background-color: #1386b0;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            transition: all 0.3s;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Header mobile */
        .mobile-header {
            display: none;
            background-color: #1386b0;
            padding: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-container {
                transform: translateX(-100%);
            }

            .sidebar-container.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-header {
                display: block;
            }
        }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Content padding untuk kompensasi header mobile */
        .content-wrapper {
            padding: 20px;
            margin-top: 0;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                margin-top: 70px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @auth
        <!-- Include Mobile Header -->
        @include('layouts.partials.header')

        <!-- Sidebar Overlay untuk Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar Container -->
        <div class="sidebar-container" id="sidebar">
            @include('layouts.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const mainContent = document.getElementById('mainContent');

                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');

                if (window.innerWidth <= 768) {
                    if (sidebar.classList.contains('show')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = 'auto';
                    }
                }
            }

            // Close sidebar when clicking on a link in mobile view
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            toggleSidebar();
                        }
                    });
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');

                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            });
        </script>
    @else
        <!-- Content untuk guest (belum login) -->
        <main>
            @yield('content')
        </main>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
