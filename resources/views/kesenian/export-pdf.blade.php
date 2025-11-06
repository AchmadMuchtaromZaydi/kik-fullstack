<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Kesenian Semua Kecamatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
        }

        .header p {
            margin: 3px 0;
        }

        .kecamatan-header {
            background-color: #e8f4fd;
            padding: 5px;
            margin: 10px 0 5px 0;
            border-left: 4px solid #2E86AB;
            font-weight: bold;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
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
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
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
            KECAMATAN: {{ $kecamatan }}
        </div>

        <table>
            <thead>
                <tr>
                    <th width="25">No</th>
                    <th>Nama Organisasi</th>
                    <th>Nomor Induk</th>
                    <th>Jenis Kesenian</th>
                    <th>Alamat</th>
                    <th>Ketua</th>
                    <th>Tgl Daftar</th>
                    <th>Tgl Expired</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKesenian as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->nomor_induk ?? '-' }}</td>
                        <td>{{ $item->nama_jenis_kesenian }}</td>
                        <td>
                            {{ $item->alamat }}
                            @if ($item->desa)
                                <br><small>Desa {{ $item->desa }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $item->nama_ketua }}
                            @if ($item->no_telp_ketua)
                                <br><small>{{ $item->no_telp_ketua }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->tanggal_daftar)
                                {{ $item->tanggal_daftar->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->tanggal_expired)
                                {{ $item->tanggal_expired->format('d/m/Y') }}
                            @else
                                -
                            @endif
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
                                $color = $statusColors[$item->status] ?? '';
                                $text = $statusTexts[$item->status] ?? $item->status;
                            @endphp
                            <span class="badge {{ $color }}">{{ $text }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

    <div class="footer">
        Total Data: {{ $dataByKecamatan->flatten()->count() }} Organisasi<br>
        Total Kecamatan: {{ $dataByKecamatan->count() }}<br>
        Dicetak pada: {{ $tanggalExport }}
    </div>
</body>

</html>
