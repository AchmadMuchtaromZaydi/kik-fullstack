<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>List Kesenian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-alamat {
            max-width: 300px;
            white-space: normal;
            word-wrap: break-word;
        }
    </style>
</head>

<body class="p-4">
    <div class="container">
        <h4 class="mb-4">List Kesenian</h4>

        <!-- Form Filter -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control" placeholder="Cari..."
                        value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <select name="jenis_kesenian" class="form-control">
                        <option value="">Semua Jenis Kesenian</option>
                        @foreach ($jenisKesenian as $jenis)
                            <option value="{{ $jenis }}"
                                {{ request('jenis_kesenian') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="kecamatan" class="form-control">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($kecamatanList as $kec)
                            <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                                {{ $kec }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('admin.kesenian.index') }}" class="btn btn-secondary w-100 mt-1">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Organisasi</th>
                        <th>Nomor Induk</th>
                        <th>Jenis Kesenian</th>
                        <th>Alamat</th>
                        <th>Ketua</th>
                        <th>Tgl Daftar</th>
                        <th>Tgl Expired</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataKesenian as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>
                                @if ($item->nomor_induk && $item->nomor_induk != 'Belum ada')
                                    {{ $item->nomor_induk }}
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->nama_jenis_kesenian ?? '-' }}</strong>
                                @if ($item->nama_sub_kesenian)
                                    <br><small class="text-muted">{{ $item->nama_sub_kesenian }}</small>
                                @endif
                            </td>
                            <td class="table-alamat">
                                <!-- TAMPILKAN LANGSUNG ALAMAT DARI DATABASE -->
                                <small>{{ $item->alamat ?? '-' }}</small>
                            </td>
                            <td>
                                @if ($item->nama_ketua && $item->nama_ketua != '-')
                                    {{ $item->nama_ketua }}
                                    @if ($item->no_telp_ketua && $item->no_telp_ketua != '-')
                                        <br><small class="text-muted">{{ $item->no_telp_ketua }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->tanggal_daftar)
                                    {{ $item->tanggal_daftar->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->tanggal_expired)
                                    {{ $item->tanggal_expired->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'Request' => 'bg-warning',
                                        'Allow' => 'bg-success',
                                        'Denny' => 'bg-danger',
                                        'DataLama' => 'bg-secondary',
                                    ];
                                    $class = $statusClass[$item->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $class }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.kesenian.show', $item->id) }}"
                                    class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.kesenian.edit', $item->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</body>

</html>
