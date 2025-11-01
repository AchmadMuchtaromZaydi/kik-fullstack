<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kartu Induk Kesenian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1386b0, #0d5a7a);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Work Sans', sans-serif;
        }

        .register-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo img {
            max-width: 150px;
        }
    </style>
</head>

<body>
    <div class="register-card p-4">
        <div class="logo">
            <img src="{{ asset('assets/img/logo-white.png') }}" alt="KIK Logo">
            <h4 class="mt-3">Daftar Akun Baru</h4>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('auth.register.post') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="whatsapp" class="form-label">Nomor WhatsApp (Opsional)</label>
                <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}"
                    placeholder="Contoh: 628123456789">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3"
                style="background-color: #1386b0; border-color: #1386b0;">
                Daftar
            </button>

            <div class="text-center mt-3">
                <a href="{{ route('auth.login') }}">Sudah punya akun? Login di sini</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
