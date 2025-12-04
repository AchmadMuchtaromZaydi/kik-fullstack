<!DOCTYPE html>
<html>

<head>
    <title><?php echo e($subject); ?></title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #1386b0;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            background: #f9f9f9;
            padding: 20px;
        }

        .code {
            font-size: 2rem;
            font-weight: bold;
            color: #1386b0;
            text-align: center;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Kartu Induk Kesenian Banyuwangi</h1>
        </div>

        <div class="content">
            <h2>Halo <?php echo e($recipient_name); ?>,</h2>

            <p><?php echo e($pesan); ?></p>

            <div class="code">
                <?php echo e($code); ?>

            </div>

            <p>Masukkan kode di atas pada halaman verifikasi untuk mengaktifkan akun Anda.</p>

            <p><strong>Perhatian:</strong> Kode ini akan kedaluwarsa dalam 24 jam.</p>

            <p>Jika Anda tidak melakukan pendaftaran, abaikan email ini.</p>
        </div>

        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> Kartu Induk Kesenian Banyuwangi. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
<?php /**PATH D:\New Code\kik-fullstack\resources\views/emails/verification.blade.php ENDPATH**/ ?>