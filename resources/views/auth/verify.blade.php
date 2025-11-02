<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Kartu Induk Kesenian Banyuwangi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1386b0;
            --secondary-color: #0d5a7a;
            --success-color: #198754;
            --warning-color: #ffc107;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .verify-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            overflow: hidden;
        }

        .verify-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .verify-body {
            padding: 40px;
        }

        .logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 50%;
            padding: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .welcome-text {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid rgba(255, 255, 255, 0.3);
        }

        .welcome-text p {
            margin-bottom: 0;
            font-size: 13px;
            line-height: 1.5;
        }

        .status-card {
            background: linear-gradient(135deg, #e7f3ff, #d1e7ff);
            border-left: 4px solid var(--primary-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .code-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }

        .code-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .code-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(19, 134, 176, 0.25);
            transform: scale(1.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(19, 134, 176, 0.4);
        }

        .timer {
            font-size: 14px;
            color: #6c757d;
            margin-top: 10px;
        }

        @media (max-width: 576px) {
            .verify-body {
                padding: 30px 20px;
            }

            .verify-header {
                padding: 20px;
            }

            .code-input {
                width: 45px;
                height: 55px;
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="verify-card">
        <div class="verify-header">
            <div class="logo">
                <img src="{{ asset('assets/img/logo-white.png') }}" alt="KIK Logo"
                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNzAiIGhlaWdodD0iNzAiIHZpZXdCb3g9IjAgMCA3MCA3MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjcwIiBoZWlnaHQ9IjcwIiByeD0iMzUiIGZpbGw9IiMxMzg2QjAiLz4KPHN2ZyB4PSIxNSIgeT0iMTUiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiPgo8cGF0aCBkPSJNOCAxNlY4TDE2IDEyTDggMTZaIi8+Cjwvc3ZnPgo8L3N2Zz4K'">
            </div>
            <h4 class="mb-2">Kartu Induk Kesenian Banyuwangi</h4>

            <!-- Narasi Formal -->
            <div class="welcome-text">
                <p class="mb-0">
                    Untuk menggunakan layanan Aplikasi Kartu Induk Kesenian Banyuwangi,
                    Anda harus memverifikasi email terlebih dahulu. Kode verifikasi telah
                    dikirim ke email Anda.
                </p>
            </div>
        </div>

        <div class="verify-body">
            <!-- Status Information -->
            <div class="status-card">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle text-primary fs-5 me-3 mt-1"></i>
                    <div>
                        <h6 class="mb-2 fw-semibold">Status Pendaftaran</h6>
                        <p class="mb-0 small">
                            Akun Anda <strong class="text-primary">belum aktif</strong> sampai email diverifikasi.
                            Setelah verifikasi berhasil, akun akan aktif secara otomatis dan Anda bisa login.
                        </p>
                    </div>
                </div>
            </div>

            @if (session('email_error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Gagal Mengirim Email!</strong> {{ session('email_error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle me-2"></i>
                    <strong>Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('auth.verify.post') }}" id="verifyForm">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold">Email Terdaftar</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control with-icon" id="email" name="email"
                            value="{{ $email }}" readonly>
                    </div>
                    <div class="form-text">
                        <i class="fas fa-paper-plane me-1"></i>
                        Kode verifikasi telah dikirim ke email ini
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Kode Verifikasi (6 digit)</label>
                    <div class="code-inputs">
                        @for ($i = 1; $i <= 6; $i++)
                            <input type="text" class="code-input" name="code[]" maxlength="1"
                                data-index="{{ $i }}" autocomplete="off">
                        @endfor
                    </div>
                    <div class="form-text text-center">
                        <i class="fas fa-key me-1"></i>
                        Masukkan 6 digit kode dari email Anda
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3" id="verifyBtn">
                    <i class="fas fa-check-circle me-2"></i>Verifikasi & Aktifkan Akun
                </button>

                <div class="text-center">
                    <p class="mb-2">
                        Tidak menerima email?
                        <a href="#" class="text-decoration-none fw-semibold"
                            onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                            <i class="fas fa-redo me-1"></i>Kirim ulang kode
                        </a>
                    </p>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>Kode verifikasi berlaku 24 jam
                    </small>
                </div>

                <div class="text-center mt-4 pt-3 border-top">
                    <a href="{{ route('auth.login') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                    </a>
                </div>
            </form>

            <form id="resend-form" action="{{ route('auth.resend.code') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus and navigation for code inputs
        const codeInputs = document.querySelectorAll('.code-input');

        codeInputs.forEach((input, index) => {
            // Auto-focus first input
            if (index === 0) {
                input.focus();
            }

            // Handle input
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1) {
                    if (index < codeInputs.length - 1) {
                        codeInputs[index + 1].focus();
                    }
                }
            });

            // Handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '') {
                    if (index > 0) {
                        codeInputs[index - 1].focus();
                    }
                }
            });

            // Prevent non-numeric input
            input.addEventListener('keypress', (e) => {
                if (!/^\d$/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });

        // Form submission
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Combine code inputs into single field
            const codeArray = Array.from(codeInputs).map(input => input.value);
            const code = codeArray.join('');

            if (code.length !== 6) {
                alert('Harap masukkan semua 6 digit kode verifikasi');
                return;
            }

            // Create hidden input for code
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'code';
            hiddenInput.value = code;
            this.appendChild(hiddenInput);

            // Show loading state
            const verifyBtn = document.getElementById('verifyBtn');
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memverifikasi...';

            // Submit form
            this.submit();
        });

        // Resend form handling
        document.getElementById('resend-form').addEventListener('submit', function() {
            const resendLink = document.querySelector('a[onclick]');
            const originalHtml = resendLink.innerHTML;

            resendLink.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengirim...';
            resendLink.style.pointerEvents = 'none';

            setTimeout(() => {
                resendLink.innerHTML = originalHtml;
                resendLink.style.pointerEvents = 'auto';
            }, 3000);
        });
    </script>
</body>

</html>
