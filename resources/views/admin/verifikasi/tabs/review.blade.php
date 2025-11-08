{{-- resources/views/admin/verifikasi/tabs/review.blade.php --}}
<div class="card">
    <div class="card-header bg-warning text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-clipboard-check me-2"></i>Review Akhir
        </h5>
    </div>
    <div class="card-body">
        @php
            $allValid = true;
            $requiredTabs = ['data_organisasi', 'data_anggota', 'data_inventaris', 'data_pendukung'];
            $verifikasiStatus = [];

            foreach ($requiredTabs as $tab) {
                $verifikasi = $verifikasiData->where('tipe', $tab)->first();
                $status = $verifikasi ? $verifikasi->status : 'belum_divalidasi';
                $verifikasiStatus[$tab] = [
                    'status' => $status,
                    'data' => $verifikasi,
                ];
                if ($status != 'valid') {
                    $allValid = false;
                }
            }
        @endphp

        @if (!$allValid)
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Data Belum Lengkap</h5>
                <p>Berikut status verifikasi setiap bagian:</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bagian</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Keterangan</th>
                                <th>Tanggal Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requiredTabs as $tab)
                                @php
                                    $status = $verifikasiStatus[$tab]['status'];
                                    $data = $verifikasiStatus[$tab]['data'];
                                @endphp
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $tab)) }}</strong></td>
                                    <td>
                                        @if ($status == 'valid')
                                            <span class="badge bg-success">✓ Valid</span>
                                        @elseif($status == 'tdk_valid')
                                            <span class="badge bg-danger">✗ Tidak Valid</span>
                                        @else
                                            <span class="badge bg-secondary">● Belum divalidasi</span>
                                        @endif
                                    </td>
                                    <td>{{ $data->catatan ?? '-' }}</td>
                                    <td>{{ $data->keterangan ?? '-' }}</td>
                                    <td>
                                        @if ($data && $data->tanggal_review)
                                            {{ $data->tanggal_review->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center">
                <p class="text-muted">Tidak dapat menyetujui organisasi karena ada data yang belum valid.</p>
                <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi']) }}"
                    class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Lanjutkan Verifikasi
                </a>
            </div>
        @else
            <div class="alert alert-success">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <div>
                        <h5>Semua Data Valid!</h5>
                        <p class="mb-0">Berdasarkan penilaian Anda, semua data pendaftaran sudah memenuhi kriteria.
                            Organisasi dapat disetujui dan mendapatkan kartu induk kesenian.</p>
                    </div>
                </div>
            </div>

            <!-- Detail Verifikasi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Detail Verifikasi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bagian</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Tanggal Review</th>
                                    <th>Di-review oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requiredTabs as $tab)
                                    @php
                                        $data = $verifikasiStatus[$tab]['data'];
                                    @endphp
                                    <tr>
                                        <td><strong>{{ ucfirst(str_replace('_', ' ', $tab)) }}</strong></td>
                                        <td><span class="badge bg-success">✓ Valid</span></td>
                                        <td>{{ $data->catatan ?? '-' }}</td>
                                        <td>{{ $data->tanggal_review->format('d/m/Y H:i') }}</td>
                                        <td>{{ $data->reviewer->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('admin.verifikasi.approve', $organisasi->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Setujui Organisasi
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('admin.verifikasi.reject', $organisasi->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg w-100"
                            onclick="return confirm('Yakin ingin menolak organisasi ini?')">
                            <i class="fas fa-times-circle me-2"></i>Tolak Organisasi
                        </button>
                    </form>
                </div>
            </div>

            @if ($organisasi->status == 'Allow')
                <div class="text-center mt-4">
                    <a href="{{ route('admin.verifikasi.generate-card', $organisasi->id) }}" class="btn btn-primary"
                        target="_blank">
                        <i class="fas fa-id-card me-2"></i>Generate Kartu Kesenian
                    </a>
                </div>
            @endif
        @endif

        <div class="text-start mt-4">
            <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_pendukung']) }}"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>
