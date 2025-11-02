{{-- resources/views/admin/partials/stats/kesenian-list.blade.php --}}
@if ($data->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nomor Induk</th>
                    <th>Nama Organisasi</th>
                    <th>Ketua</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nomor_induk ?? '-' }}</td>
                        <td>{{ $item->nama ?? '-' }}</td>
                        <td>{{ $item->nama_ketua ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'Allow' ? 'success' : 'warning' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>{{ $item->tanggal_daftar ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <strong>Total: {{ $data->count() }} data</strong>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>Tidak ada data ditemukan.
    </div>
@endif
