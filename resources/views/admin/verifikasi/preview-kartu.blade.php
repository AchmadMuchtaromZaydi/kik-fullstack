{{-- resources/views/admin/verifikasi/preview-kartu.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Preview Kartu Induk</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .kartu-container {
            width: 400px;
            height: 600px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            border: 5px solid #2c5aa0;
        }

        .kartu-header {
            background: linear-gradient(135deg, #2c5aa0, #1e3a8a);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .kartu-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .kartu-header h3 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
        }

        .nomor-induk {
            background: #ffd700;
            color: #2c5aa0;
            padding: 8px 15px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
        }

        .kartu-body {
            padding: 25px;
        }

        .foto-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .foto-placeholder {
            width: 120px;
            height: 150px;
            background: #e0e0e0;
            border: 2px dashed #ccc;
            border-radius: 10px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 12px;
        }

        .data-section {
            margin-bottom: 15px;
        }

        .data-row {
            display: flex;
            margin-bottom: 8px;
            border-bottom: 1px dashed #e0e0e0;
            padding-bottom: 8px;
        }

        .data-label {
            font-weight: bold;
            color: #2c5aa0;
            width: 120px;
            font-size: 12px;
        }

        .data-value {
            flex: 1;
            font-size: 12px;
            color: #333;
        }

        .qrcode-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #2c5aa0;
        }

        .qrcode {
            width: 100px;
            height: 100px;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 10px;
        }

        .kartu-footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 2px solid #e0e0e0;
        }

        .footer-text {
            font-size: 10px;
            color: #666;
            margin: 2px 0;
        }

        .logo {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #2c5aa0;
            border: 2px solid #ffd700;
        }

        .stamp {
            position: absolute;
            bottom: 100px;
            right: 20px;
            width: 80px;
            height: 80px;
            border: 2px solid red;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(15deg);
            opacity: 0.8;
        }

        .stamp::before {
            content: "DISBUDPAR";
            color: red;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="kartu-container" id="kartuContainer">
        <div class="logo">KIK</div>

        <div class="kartu-header">
            <h2>KARTU INDUK KESENIAN</h2>
            <h3>KABUPATEN BANYUWANGI</h3>
            <div class="nomor-induk">{{ $organisasi->nomor_induk ?? 'BELUM ADA' }}</div>
        </div>

        <div class="kartu-body">
            <div class="foto-section">
                <div class="foto-placeholder">
                    FOTO ORGANISASI
                </div>
            </div>

            <div class="data-section">
                <div class="data-row">
                    <div class="data-label">Nama Organisasi</div>
                    <div class="data-value">{{ $organisasi->nama }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Jenis Kesenian</div>
                    <div class="data-value">{{ $organisasi->nama_jenis_kesenian }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Ketua</div>
                    <div class="data-value">{{ $organisasi->nama_ketua }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Alamat</div>
                    <div class="data-value">{{ $organisasi->alamat }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Kecamatan</div>
                    <div class="data-value">{{ $organisasi->nama_kecamatan }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Masa Berlaku</div>
                    <div class="data-value">
                        {{ $organisasi->tanggal_expired ? \Carbon\Carbon::parse($organisasi->tanggal_expired)->format('d/m/Y') : '-' }}
                    </div>
                </div>
            </div>

            <div class="qrcode-section">
                <div class="qrcode">
                    QR CODE<br>SCAN HERE
                </div>
                <div style="margin-top: 8px; font-size: 10px; color: #666;">
                    Scan untuk verifikasi keaslian
                </div>
            </div>
        </div>

        <div class="kartu-footer">
            <div class="footer-text">Kartu ini diterbitkan oleh Dinas Kebudayaan dan Pariwisata</div>
            <div class="footer-text">Kabupaten Banyuwangi</div>
            <div class="footer-text">
                Berlaku hingga:
                {{ $organisasi->tanggal_expired ? \Carbon\Carbon::parse($organisasi->tanggal_expired)->format('d F Y') : '-' }}
            </div>
        </div>

        <div class="stamp"></div>
    </div>

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        // Fungsi untuk generate kartu sebagai image
        function generateKartuImage() {
            const element = document.getElementById('kartuContainer');

            html2canvas(element, {
                scale: 3,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff'
            }).then(canvas => {
                const image = canvas.toDataURL('image/png');

                // Kirim image ke parent window (jika di modal)
                if (window.opener) {
                    window.opener.postMessage({
                        type: 'KARTU_GENERATED',
                        imageData: image,
                        organisasiId: '{{ $organisasi->id }}'
                    }, '*');
                }

                // Download otomatis
                const link = document.createElement('a');
                link.href = image;
                link.download = 'kartu_kesenian_{{ $organisasi->nomor_induk ?? $organisasi->id }}.png';
                link.click();
            });
        }

        // Auto generate saat halaman load (opsional)
        // setTimeout(generateKartuImage, 1000);
    </script>
</body>

</html>
