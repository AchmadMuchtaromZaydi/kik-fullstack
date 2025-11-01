<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Detail Kesenian</title>
</head>

<body class="p-4">
    <h4>Detail: {{ $item->nama ?? '-' }}</h4>
    <p>Nomor Induk: {{ $item->nomor_induk ?? '-' }}</p>
    <p>Alamat: {{ $item->alamat ?? '-' }}</p>
    <a href="{{ route('kesenian.index') }}">Kembali</a>
</body>

</html>
