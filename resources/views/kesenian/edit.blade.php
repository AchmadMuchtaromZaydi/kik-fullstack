<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Kesenian</title>
</head>

<body class="p-4">
    <h4>Edit: {{ $item->nama ?? '-' }}</h4>

    <form action="{{ route('kesenian.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Nama</label>
            <input type="text" name="nama" value="{{ $item->nama }}">
        </div>
        <div>
            <label>Nomor Induk</label>
            <input type="text" name="nomor_induk" value="{{ $item->nomor_induk }}">
        </div>
        <div>
            <label>Alamat</label>
            <input type="text" name="alamat" value="{{ $item->alamat }}">
        </div>
        <button>Simpan</button>
    </form>

    <a href="{{ route('kesenian.index') }}">Kembali</a>
</body>

</html>
