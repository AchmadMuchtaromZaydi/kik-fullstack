<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Kesenian by Kecamatan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    @foreach ($groupedData as $kecamatan => $items)
        <h2>Kecamatan: {{ $kecamatan }}</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Organisasi</th>
                    <th>Jenis Kesenian</th>
                    <th>Sub Kesenian</th>
                    <th>Ketua</th>
                    <th>No. Telp Ketua</th>
                    <th>Desa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama ?? '-' }}</td>
                        <td>{{ $item->jenisKesenianObj->nama ?? ($item->nama_jenis_kesenian ?? '-') }}</td>
                        <td>{{ $item->subKesenianObj->nama ?? ($item->sub_kesenian_nama ?? '-') }}</td>
                        <td>{{ $item->ketua->nama ?? ($item->nama_ketua ?? '-') }}</td>
                        <td>{{ $item->ketua->telepon ?? ($item->ketua->whatsapp ?? ($item->no_telp_ketua ?? '-')) }}
                        </td>
                        <td>{{ $item->desaWilayah->nama ?? ($item->nama_desa ?? '-') }}</td>
                        <td>{{ ucfirst($item->status ?? '-') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>

</html>
