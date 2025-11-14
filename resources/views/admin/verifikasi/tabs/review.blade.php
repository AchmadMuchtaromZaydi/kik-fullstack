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
                $status = $verifikasi->status ?? 'belum_divalidasi';

                $verifikasiStatus[$tab] = [
                    'status' => $status,
                    'data' => $verifikasi,
                ];

                if ($status !== 'valid') {
                    $allValid = false;
                }
            }
        @endphp

        {{-- Jika ada data yang belum valid --}}
        @if (!$allValid)
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Data Belum Lengkap</h5>
                <p>Berikut status verifikasi setiap bagian:</p>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
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
                                        @switch($status)
                                            @case('valid')
                                                <span class="badge bg-success">✓ Valid</span>
                                            @break

                                            @case('tdk_valid')
                                                <span class="badge bg-danger">✗ Tidak Valid</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">● Belum Divalidasi</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $data->catatan ?? '-' }}</td>
                                    <td>{{ $data->keterangan ?? '-' }}</td>
                                    <td>{{ $data?->tanggal_review?->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-center">
                <p class="text-muted mb-3">
                    Tidak dapat menyetujui organisasi karena ada data yang belum valid.
                </p>
                <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_organisasi']) }}"
                    class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Lanjutkan Verifikasi
                </a>
            </div>

            {{-- Jika semua data valid --}}
        @else
            <div class="alert alert-success d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                <div>
                    <h5 class="mb-1">Semua Data Valid!</h5>
                    <p class="mb-0">Semua data pendaftaran sudah memenuhi kriteria.
                        Organisasi dapat disetujui dan mendapatkan Kartu Induk Kesenian.</p>
                </div>
            </div>

            {{-- Detail Verifikasi --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Detail Verifikasi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Bagian</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Tanggal Review</th>
                                    <th>Reviewer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requiredTabs as $tab)
                                    @php $data = $verifikasiStatus[$tab]['data']; @endphp
                                    <tr>
                                        <td><strong>{{ ucfirst(str_replace('_', ' ', $tab)) }}</strong></td>
                                        <td><span class="badge bg-success">✓ Valid</span></td>
                                        <td>{{ $data->catatan ?? '-' }}</td>
                                        <td>{{ $data?->tanggal_review?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td>{{ $data?->reviewer?->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tombol Approve & Reject --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="{{ route('admin.verifikasi.approve', $organisasi->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Setujui Organisasi
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('admin.verifikasi.reject', $organisasi->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menolak organisasi ini?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-times-circle me-2"></i>Tolak Organisasi
                        </button>
                    </form>
                </div>
            </div>


            {{-- ============================================= --}}
            {{-- TOMBOL TAMPILKAN KARTU --}}
            {{-- ============================================= --}}
            @if ($organisasi->status === 'Allow')
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#previewKartuModal"
                        data-kartu-url="{{ route('admin.verifikasi.kartu', $organisasi->id) }}">
                        <i class="fas fa-id-card me-2"></i> Tampilkan Kartu Induk
                    </button>
                </div>
            @endif


            {{-- ============================================= --}}
            {{-- MODAL PREVIEW KARTU --}}
            {{-- ============================================= --}}
            {{-- ============================================= --}}
            <div class="modal fade" id="previewKartuModal" tabindex="-1" aria-labelledby="previewKartuModalLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 14px;">

                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="previewKartuModalLabel">
                                <i class="fas fa-id-card me-2"></i>Preview Kartu Induk Kesenian
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-center" style="background:#f5f5f5; padding:20px;">
                            <div id="previewKartuContent">
                                <div class="spinner-border text-primary" style="width:3rem;height:3rem"></div>
                                <p class="mt-3 text-muted">Sedang men-generate kartu...</p>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Tutup
                            </button>
                            <a href="#" class="btn btn-success disabled" id="btnDownloadKartu"
                                download="kartu_induk_{{ $organisasi->nomor_induk ?? $organisasi->id }}.png">
                                <i class="fas fa-download me-2"></i>Download Gambar
                            </a>
                        </div>

                    </div>
                </div>
            </div>


        @endif {{-- Akhir dari @else --}}

        {{-- Tombol Kembali --}}
        <div class="text-start mt-4">
            <a href="{{ route('admin.verifikasi.show', ['id' => $organisasi->id, 'tab' => 'data_pendukung']) }}"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var previewModalEl = document.getElementById('previewKartuModal');
            if (!previewModalEl) return;

            var modal = new bootstrap.Modal(previewModalEl);
            var contentArea = document.getElementById('previewKartuContent');
            var downloadButton = document.getElementById('btnDownloadKartu');

            // Simpan spinner awal
            var spinnerHtml = contentArea.innerHTML;

            // Saat modal DIBUKA
            previewModalEl.addEventListener('show.bs.modal', function(event) {

                var button = event.relatedTarget;
                var kartuUrl = button.getAttribute('data-kartu-url');

                if (!kartuUrl) {
                    contentArea.innerHTML = '<p class="text-danger">Gagal mendapatkan URL kartu.</p>';
                    return;
                }

                var downloadUrl = kartuUrl + '?download=true';

                var img = new Image();
                img.className = 'img-fluid';
                img.style.borderRadius = '12px';
                img.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';

                // ⭐ PERBAIKAN UTAMA → Hilangkan crop, biarkan gambar penuh
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                img.style.maxHeight = 'none';

                // Ketika gambar sukses dimuat
                img.onload = function() {
                    contentArea.innerHTML = '';
                    contentArea.appendChild(img);

                    downloadButton.setAttribute('href', downloadUrl);
                    downloadButton.classList.remove('disabled');
                };

                // Jika gagal memuat
                img.onerror = function() {
                    contentArea.innerHTML =
                        '<p class="text-danger">Gagal memuat preview kartu. Silakan coba lagi.</p>';
                };

                // Tambahkan cache-buster
                img.src = kartuUrl + '?v=' + new Date().getTime();
            });

            // Saat modal DITUTUP → reset lagi
            previewModalEl.addEventListener('hidden.bs.modal', function() {
                contentArea.innerHTML = spinnerHtml;

                downloadButton.setAttribute('href', '#');
                downloadButton.classList.add('disabled');
            });
        });
    </script>
@endpush
