<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Kesenian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h4>Edit: {{ $item->nama ?? '-' }}</h4>

        <form action="{{ route('admin.kesenian.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Organisasi</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Induk</label>
                        <input type="text" name="nomor_induk" class="form-control"
                            value="{{ old('nomor_induk', $item->nomor_induk) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kesenian</label>
                        <select name="jenis_kesenian" class="form-control" required>
                            <option value="">Pilih Jenis Kesenian</option>
                            @foreach ($jenisKesenian as $jenis)
                                <option value="{{ $jenis }}"
                                    {{ old('jenis_kesenian', $item->nama_jenis_kesenian) == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <select name="kecamatan" class="form-control" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($kecamatanList as $kec)
                                <option value="{{ $kec }}"
                                    {{ old('kecamatan', $item->nama_kecamatan) == $kec ? 'selected' : '' }}>
                                    {{ $kec }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" required>{{ old('alamat', $item->alamat) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Desa</label>
                        <input type="text" name="desa" class="form-control"
                            value="{{ old('desa', $item->desa) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="Request" {{ old('status', $item->status) == 'Request' ? 'selected' : '' }}>
                                Request</option>
                            <option value="Allow" {{ old('status', $item->status) == 'Allow' ? 'selected' : '' }}>
                                Allow</option>
                            <option value="Denny" {{ old('status', $item->status) == 'Denny' ? 'selected' : '' }}>
                                Denny</option>
                            <option value="DataLama"
                                {{ old('status', $item->status) == 'DataLama' ? 'selected' : '' }}>Data Lama</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.kesenian.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>
