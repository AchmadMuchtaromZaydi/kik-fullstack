<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Kartu Induk Kesenian Banyuwangi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1386b0;
            --secondary-color: #0d5a7a;
            --accent-color: #f8f9fa;
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

        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            text-align: center;
        }

        .auth-body {
            padding: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 50%;
            padding: 15px;
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
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid rgba(255, 255, 255, 0.3);
        }

        .welcome-text p {
            margin-bottom: 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(19, 134, 176, 0.25);
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

        .password-strength {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak {
            background: #dc3545;
            width: 25%;
        }

        .strength-fair {
            background: #fd7e14;
            width: 50%;
        }

        .strength-good {
            background: #ffc107;
            width: 75%;
        }

        .strength-strong {
            background: #198754;
            width: 100%;
        }

        .input-group-text {
            background: var(--accent-color);
            border: 2px solid #e9ecef;
            border-right: none;
        }

        .form-control.with-icon {
            border-left: none;
        }

        .auth-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            margin-top: 20px;
        }

        @media (max-width: 576px) {
            .auth-body {
                padding: 30px 20px;
            }

            .auth-header {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="logo">
                <img src="<?php echo e(asset('assets/img/logo-white.png')); ?>" alt="KIK Logo"
                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiByeD0iNDAiIGZpbGw9IiMxMzg2QjAiLz4KPHN2ZyB4PSIyMCIgeT0iMjAiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiPgo8cGF0aCBkPSJNOCAxNlY4TDE2IDEyTDggMTZaIi8+Cjwvc3ZnPgo8L3N2Zz4K'">
            </div>
            <h4 class="mb-2">Kartu Induk Kesenian Banyuwangi</h4>

            <!-- Narasi Formal -->
            <div class="welcome-text">
                <p class="mb-0">
                    Untuk menggunakan layanan Aplikasi Kartu Induk Kesenian Banyuwangi,
                    Anda harus memiliki akun terlebih dahulu. Silakan lengkapi formulir
                    pendaftaran di bawah ini untuk membuat akun baru.
                </p>
            </div>
        </div>

        <div class="auth-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('auth.register.post')); ?>" id="registerForm">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" class="form-control with-icon" id="name" name="name"
                            value="<?php echo e(old('name')); ?>" placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control with-icon" id="email" name="email"
                            value="<?php echo e(old('email')); ?>" placeholder="contoh@email.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="whatsapp" class="form-label fw-semibold">
                        Nomor WhatsApp
                        <small class="text-muted">(Opsional)</small>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fab fa-whatsapp"></i>
                        </span>
                        <input type="text" class="form-control with-icon" id="whatsapp" name="whatsapp"
                            value="<?php echo e(old('whatsapp')); ?>" placeholder="628123456789">
                    </div>
                    <div class="form-text">Contoh: 628123456789</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control with-icon" id="password" name="password"
                            placeholder="Minimal 6 karakter" required>
                        <span class="input-group-text toggle-password" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-strength mt-2">
                        <div class="password-strength-bar" id="passwordStrength"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control with-icon" id="password_confirmation"
                            name="password_confirmation" placeholder="Ketik ulang password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3" id="submitBtn">
                    <i class="fas fa-user-plus me-2"></i>Daftar Akun
                </button>

                <div class="auth-footer">
                    <p class="mb-0">
                        Sudah memiliki akun?
                        <a href="<?php echo e(route('auth.login')); ?>" class="text-decoration-none fw-semibold">
                            Masuk di sini
                        </a>
                    </p>

                </div>
            </form>

            <div class="footer text-center">
                &copy; <?php echo e(date('Y')); ?> Kartu Induk Kesenian Banyuwangi. Semua hak dilindungi.
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const passwordInput = this.closest('.input-group').querySelector('input');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            let strength = 0;

            if (password.length >= 6) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/\d/)) strength += 25;
            if (password.match(/[^a-zA-Z\d]/)) strength += 25;

            strengthBar.className = 'password-strength-bar';
            if (strength <= 25) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 50) {
                strengthBar.classList.add('strength-fair');
            } else if (strength <= 75) {
                strengthBar.classList.add('strength-good');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });

        // Form submission loading state
        document.getElementById('registerForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftarkan...';
        });
    </script>
</body>

</html>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/auth/register.blade.php ENDPATH**/ ?>