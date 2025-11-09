{{-- resources/views/admin/kesenian/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Kesenian')
@section('page-title', 'Data Kesenian')

@section('content')
    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Organisasi Kesenian</h5>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import me-2"></i>Import Data
                </button>
            </div>

            <div class="card-body">
                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('admin.kesenian.index') }}" class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="q" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="q" name="q"
                            placeholder="Cari nama, jenis, ketua, alamat..." value="{{ request('q') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="jenis_kesenian" class="form-label">Filter Jenis Kesenian</label>
                        <select class="form-select" id="jenis_kesenian" name="jenis_kesenian">
                            <option value="">Semua Jenis</option>
                            @foreach ($jenisKesenianList as $jenis)
                                <option value="{{ $jenis }}"
                                    {{ request('jenis_kesenian') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="kecamatan" class="form-label">Filter Kecamatan</label>
                        <select class="form-select" id="kecamatan" name="kecamatan">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($kecamatanList as $kec)
                                <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                                    {{ $kec }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Cari & Filter
                            </button>
                            <a href="{{ route('admin.kesenian.index') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-2"></i>Reset
                            </a>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download me-2"></i>Download
                            </button>
                            <ul class="dropdown-menu">
                                <li><button type="button" id="btnDownloadPdf" class="dropdown-item">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>PDF</button></li>
                                <li><button type="button" id="btnDownloadExcel" class="dropdown-item">
                                        <i class="fas fa-file-excel text-success me-2"></i>Excel</button></li>
                            </ul>
                        </div>
                    </div>
                </form>

                {{-- Info Data --}}
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Menampilkan <strong>{{ $dataKesenian->count() }}</strong> dari total
                    <strong>{{ $dataKesenian->total() }}</strong> data organisasi kesenian.
                </div>

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>Nama Kesenian</th>
                                <th>Nomor Induk</th>
                                <th>Jenis</th>
                                <th>Alamat</th>
                                <th>Ketua</th>
                                <th>Daftar</th>
                                <th>Expired</th>
                                <th>Status</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataKesenian as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $dataKesenian->firstItem() + $i }}</td>
                                    <td>{{ $item->nama ?? '-' }}</td>
                                    <td><strong class="text-primary">{{ $item->nomor_induk ?? 'Belum ada' }}</strong></td>
                                    <td>{{ $item->jenis_kesenian_nama }}</td>
                                    <td><small>{{ $item->alamat ?? '-' }}</small></td>
                                    <td>
                                        <strong>{{ $item->ketua->nama ?? '-' }}</strong><br>
                                        <small class="text-muted">{{ $item->ketua->no_telp ?? '' }}</small>
                                    </td>
                                    <td>{{ $item->tanggal_daftar ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        @if ($item->tanggal_expired)
                                            @php $exp = \Carbon\Carbon::parse($item->tanggal_expired); @endphp
                                            @if ($exp->isPast())
                                                <span class="badge bg-danger small">{{ $exp->format('d/m/Y') }}</span>
                                            @elseif($exp->diffInDays(now()) <= 30)
                                                <span
                                                    class="badge bg-warning text-dark small">{{ $exp->format('d/m/Y') }}</span>
                                            @else
                                                <span class="small">{{ $exp->format('d/m/Y') }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'Request' => 'warning',
                                                'Allow' => 'success',
                                                'Denny' => 'danger',
                                                'DataLama' => 'info',
                                            ];
                                            $statusTexts = [
                                                'Request' => 'Menunggu',
                                                'Allow' => 'Diterima',
                                                'Denny' => 'Ditolak',
                                                'DataLama' => 'Data Lama',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$item->status] ?? 'secondary' }}">
                                            {{ $statusTexts[$item->status] ?? $item->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.kesenian.edit', $item->id) }}"
                                                class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if ($item->status == 'Request')
                                                <a href="{{ route('admin.verifikasi.show', $item->id) }}"
                                                    class="btn btn-info">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.kesenian.destroy', $item->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Hapus data?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $dataKesenian->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Modal Import --}}
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.kesenian.import.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Import Data Kesenian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih file Excel</label>
                            <input type="file" name="file" class="form-control" id="file" required
                                accept=".xlsx,.xls,.csv">
                        </div>
                        <div class="alert alert-warning small">
                            Pastikan urutan kolom sesuai format import.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const q = document.getElementById('q').value;
                const jenis = document.getElementById('jenis_kesenian').value;
                const kecamatan = document.getElementById('kecamatan').value;

                document.getElementById('btnDownloadPdf').addEventListener('click', () => {
                    const url =
                        `{{ route('admin.kesenian.download.pdf') }}?q=${encodeURIComponent(q)}&jenis_kesenian=${encodeURIComponent(jenis)}&kecamatan=${encodeURIComponent(kecamatan)}`;
                    window.open(url, '_blank');
                });

                document.getElementById('btnDownloadExcel').addEventListener('click', () => {
                    const url =
                        `{{ route('admin.kesenian.download.excel') }}?q=${encodeURIComponent(q)}&jenis_kesenian=${encodeURIComponent(jenis)}&kecamatan=${encodeURIComponent(kecamatan)}`;
                    window.open(url, '_blank');
                });
            });
        </script>
    @endpush
@endsection
