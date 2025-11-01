<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>List Kesenian</title>
</head>

<body class="p-4">
    <h4>List Kesenian</h4>
    <a href="{{ url('/') }}">Kembali</a>
    <ul>
        @forelse($dataKesenian as $it)
            <li>{{ $it->nama }} — <a href="{{ route('kesenian.show', $it->id) }}">View</a> | <a
                    href="{{ route('kesenian.edit', $it->id) }}">Edit</a></li>
        @empty
            <li>Belum ada data.</li>
        @endforelse
    </ul>
</body>

</html>
