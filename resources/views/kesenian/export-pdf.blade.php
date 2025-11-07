<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Data Kesenian Semua Kecamatan</title>
    <style>
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 6px;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
        }

        .header p {
            margin: 2px 0;
            font-size: 9px;
        }

        .kecamatan-header {
            background-color: #e8f4fd;
            padding: 6px;
            margin-top: 12px;
            margin-bottom: 4px;
            border-left: 4px solid #2E86AB;
            font-weight: bold;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 3px 5px;
            word-wrap: break-word;
            vertical-align: top;
        }

        th {
            background-color: #2E86AB;
            color: #fff;
            font-size: 9px;
            text-align: center;
        }

        td {
            font-size: 8.5px;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            display: inline-block;
        }

        .bg-success {
            background-color: #d4edda;
            color: #155724;
        }

        .bg-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .bg-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .bg-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .footer {
            margin-top: 10px;
            text-align: right;
            font-size: 8.5px;
            color: #666;
            border-top: 1px solid #aaa;
            padding-top: 4px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA ORGANISASI KESENIAN SEMUA KECAMATAN</h1>
        <p>Tanggal Export: {{ $tanggalExport }}</p>
    </div>

    @foreach ($dataByKecamatan as $kecamatan => $dataKesenian)
        <div class="kecamatan-header">
            KECAMATAN: {{ $kecamatan ?? 'Tidak Terkategori' }}
        </div>

        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="17%">Nama Organisasi</th>
                    <th width="10%">Nomor Induk</th>
                    <th width="13%">Jenis Kesenian</th>
                    <th width="23%">Alamat</th>
                    <th width="13%">Ketua</th>
                    <th width="8%">Tgl Daftar</th>
                    <th width="8%">Tgl Expired</th>
                    <th width="7%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKesenian as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->nomor_induk ?? '-' }}</td>
                        <td>{{ $item->nama_jenis_kesenian ?? '-' }}</td>
                        <td>
                            {{ $item->alamat }}
                            @if ($item->nama_desa || $item->desa)
                                <br><small>Desa {{ $item->nama_desa ?? $item->desa }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $item->nama_ketua }}
                            @if ($item->no_telp_ketua)
                                <br><small>{{ $item->no_telp_ketua }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $item->tanggal_daftar && $item->tanggal_daftar != '0000-00-00'
                                ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td class="text-center">
                            {{ $item->tanggal_expired && $item->tanggal_expired != '0000-00-00'
                                ? \Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y')
                                : '-' }}
                        </td>
                        <td class="text-center">
                            @php
                                $statusColors = [
                                    'Request' => 'bg-warning',
                                    'Allow' => 'bg-success',
                                    'Denny' => 'bg-danger',
                                    'DataLama' => 'bg-info',
                                ];
                                $statusTexts = [
                                    'Request' => 'Menunggu',
                                    'Allow' => 'Diterima',
                                    'Denny' => 'Ditolak',
                                    'DataLama' => 'Data Lama',
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$item->status] ?? '' }}">
                                {{ $statusTexts[$item->status] ?? $item->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="footer">
        Total Data: {{ $dataByKecamatan->flatten()->count() }} Organisasi |
        Total Kecamatan: {{ $dataByKecamatan->count() }}<br>
        Dicetak pada: {{ $tanggalExport }}
    </div>
</body>

</html>
