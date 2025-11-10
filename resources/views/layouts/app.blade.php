{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KIK - Sistem Kesenian')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display-swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    {{-- Memuat Header --}}
    @include('layouts.partials.header')

    {{-- Memuat Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Konten Utama --}}
    <div class="main-content d-flex flex-column min-vh-100">
        {{-- flex-grow-1 akan mendorong wrapper footer ke bawah --}}
        <main class="p-3 p-md-4 flex-grow-1">
            @yield('content')
        </main>

        {{-- Wrapper BARU untuk footer box --}}
        {{-- Padding ini (px, pb) memastikan footer box punya jarak dari tepi
             dan sejajar dengan padding konten di atasnya --}}
        <div class="px-3 px-md-4 pb-3 pb-md-4">
            @include('layouts.partials.footer')
        </div>
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
