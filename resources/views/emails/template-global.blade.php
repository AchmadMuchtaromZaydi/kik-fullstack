<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pendaftaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h1 {
            color: #333333;
        }

        p {
            color: #555555;
        }

        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #dddddd;
            border-radius: 5px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .footer {
            color: #777777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Informasi Pendaftaran</h1>
        <p>Halo {{ $data['recipient_name'] }}</p>

        <p>{{ $data['message'] }}</p>

        <div class="verification-code">{{ $data['status'] }}</div>

        <p>Jika Anda tidak mendaftar untuk layanan ini, silakan abaikan email ini.</p>
        <p class="footer">Salam hangat,<br>Dinas Kebudayaan dan Pariwisata <br> Kabupaten Banyuwangi</p>
    </div>
</body>

</html>
