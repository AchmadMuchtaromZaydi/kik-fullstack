<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Kartu Induk Kesenian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1386b0, #0d5a7a);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Work Sans', sans-serif;
        }

        .verify-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo img {
            max-width: 150px;
        }

        .status-info {
            background: #e7f3ff;
            border-left: 4px solid #1386b0;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="verify-card p-4">
        <div class="logo">
            <img src="{{ asset('assets/img/logo-white.png') }}" alt="KIK Logo">
            <h4 class="mt-3">Verifikasi Email</h4>
        </div>

        <!-- Informasi Status -->
        <div class="status-info">
            <h6 class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Status Pendaftaran</h6>
            <p class="mb-0 small">
                Akun Anda <strong>belum aktif</strong> sampai email diverifikasi.
                Setelah verifikasi, akun akan aktif secara otomatis.
                <br><strong>Kode verifikasi telah dikirim ke email Anda.</strong>
            </p>
        </div>

        @if (session('email_error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Gagal Mengirim Email!</strong> {{ session('email_error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div><i class="fas fa-times-circle me-2"></i>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('auth.verify.post') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $email }}"
                    readonly required>
                <div class="form-text">Email yang digunakan untuk pendaftaran</div>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Kode Verifikasi (6 digit)</label>
                <input type="number" class="form-control" id="code" name="code"
                    placeholder="Masukkan 6 digit kode dari email" required autofocus>
                <div class="form-text">
                    <i class="fas fa-envelope me-1"></i>
                    Kode verifikasi telah dikirim ke email <strong>{{ $email }}</strong>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3"
                style="background-color: #1386b0; border-color: #1386b0;">
                <i class="fas fa-check-circle me-2"></i>Verifikasi & Aktifkan Akun
            </button>

            <div class="text-center">
                <p class="mb-2">Tidak menerima email?
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                        <i class="fas fa-redo me-1"></i>Kirim ulang kode verifikasi
                    </a>
                </p>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>Kode verifikasi berlaku 24 jam
                </small>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('auth.login') }}"><i class="fas fa-arrow-left me-1"></i>Kembali ke Login</a>
            </div>
        </form>

        <form id="resend-form" action="{{ route('auth.resend.code') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto focus pada input kode
        document.getElementById('code').focus();

        // Auto submit form resend
        document.getElementById('resend-form').addEventListener('submit', function() {
            // Show loading state
            const button = document.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim ulang...';

            // Reset button setelah 3 detik
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = originalText;
            }, 3000);
        });
    </script>
</body>

</html>
