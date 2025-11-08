
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kartu Organisasi Kesenian - <?php echo e($organisasi->nomor_induk); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .card {
            border: 2px solid #333;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .content {
            display: flex;
            justify-content: space-between;
        }

        .left-section {
            width: 70%;
        }

        .right-section {
            width: 25%;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .qrcode {
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
        }

        .field {
            margin-bottom: 8px;
        }

        .field-label {
            font-weight: bold;
        }

        .nomor-induk {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <h2>KARTU ORGANISASI KESENIAN</h2>
            <h3>KABUPATEN BANYUWANGI</h3>
            <div class="nomor-induk">
                No. Induk: <?php echo e($organisasi->nomor_induk); ?>

            </div>
        </div>

        <div class="content">
            <div class="left-section">
                <div class="field">
                    <span class="field-label">Nama Organisasi:</span> <?php echo e($organisasi->nama); ?>

                </div>
                <div class="field">
                    <span class="field-label">Jenis Kesenian:</span> <?php echo e($organisasi->nama_jenis_kesenian); ?>

                </div>
                <div class="field">
                    <span class="field-label">Ketua:</span> <?php echo e($organisasi->nama_ketua); ?>

                </div>
                <div class="field">
                    <span class="field-label">Alamat:</span> <?php echo e($organisasi->alamat); ?>

                </div>
                <div class="field">
                    <span class="field-label">Kecamatan:</span> <?php echo e($organisasi->nama_kecamatan); ?>

                </div>
                <div class="field">
                    <span class="field-label">Masa Berlaku:</span>
                    <?php echo e($organisasi->tanggal_expired ? $organisasi->tanggal_expired->format('d/m/Y') : '-'); ?>

                </div>
            </div>

            <div class="right-section">
                <div class="qrcode">
                    <!-- Ganti dengan path QR code Anda -->
                    <img src="<?php echo e(public_path('images/qrcode.png')); ?>" width="100" height="100">
                </div>
                <div style="margin-top: 10px; font-size: 10px;">
                    Scan untuk verifikasi
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Kartu ini diterbitkan oleh Dinas Kebudayaan Kabupaten Banyuwangi</p>
            <p>Berlaku hingga: <?php echo e($organisasi->tanggal_expired ? $organisasi->tanggal_expired->format('d F Y') : '-'); ?>

            </p>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\project-magang\fullstack-KIK\kik-fullstack\resources\views/admin/verifikasi/kartu.blade.php ENDPATH**/ ?>