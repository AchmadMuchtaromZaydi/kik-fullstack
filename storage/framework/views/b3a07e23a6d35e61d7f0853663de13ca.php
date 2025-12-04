<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kartu Induk Kesenian Banyuwangi</title>
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

        .input-group-text {
            background: var(--accent-color);
            border: 2px solid #e9ecef;
            border-right: none;
        }

        /* Ini untuk input password toggle */
        .input-group-text.toggle-password {
            border-left: none;
            border-right: 2px solid #e9ecef;
        }

        .form-control.with-icon {
            border-left: none;
        }

        /* Ini untuk input password toggle */
        .form-control.with-icon-right {
             border-right: none;
             border-left: 2px solid #e9ecef;
        }

        .auth-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            margin-top: 20px;
        }

        .footer {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 30px;
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

            <div class="welcome-text">
                <p class="mb-0">
                    Selamat datang kembali. Silakan masukkan email dan password Anda
                    untuk masuk ke sistem Kartu Induk Kesenian.
                </p>
            </div>
        </div>

        <div class="auth-body">

            <?php if(session('status')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('status')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Gagal Login!</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>


            <form method="POST" action="<?php echo e(route('auth.login.post')); ?>" id="loginForm">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control with-icon" id="email" name="email"
                            value="<?php echo e(old('email')); ?>" placeholder="contoh@email.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control with-icon-right" id="password" name="password"
                            placeholder="Masukkan password" required>
                        <span class="input-group-text toggle-password" style="cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3" id="submitBtn">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>

                <div class="auth-footer">
                    <p class="mb-0">
                        Belum memiliki akun?
                        <a href="<?php echo e(route('auth.register')); ?>" class="text-decoration-none fw-semibold">
                            Daftar di sini
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
            icon.addEventListener('click', function () {
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

        // Form submission loading state
        document.getElementById('loginForm').addEventListener('submit', function () {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Masuk...';
        });
    </script>
</body>

</html>
<?php /**PATH D:\New Code\kik-fullstack\resources\views/auth/login.blade.php ENDPATH**/ ?>