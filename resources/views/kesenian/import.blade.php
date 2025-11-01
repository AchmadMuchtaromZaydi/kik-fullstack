<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Import Kesenian</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body class="p-4">
    <div class="container">
        <h4>Import Data Kesenian</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kesenian.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Pilih file CSV / XLSX</label>
                <input type="file" name="file" class="form-control-file" required>
            </div>
            <a href="{{ url('/') }}" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary">Upload</button>
        </form>
    </div>
</body>

</html>
