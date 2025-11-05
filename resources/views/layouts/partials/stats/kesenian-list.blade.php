<h6 class="mb-3">{{ $title }} ({{ $data->count() }} data)</h6>

@if ($data->isEmpty())
    <div class="alert alert-info">Tidak ada data</div>
@else
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-sm table-striped table-hover">
            <thead class="table-light sticky-top">
                <tr>
                    <th width="50">No</th>
                    <th>Nama Organisasi</th>
                    <th>Nomor Induk</th>
                    <th>Jenis Kesenian</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                    @php
                        $statusClass = [
                            'Request' => 'bg-warning',
                            'Allow' => 'bg-success',
                            'Denny' => 'bg-danger',
                            'DataLama' => 'bg-secondary',
                        ];
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama ?? '-' }}</td>
                        <td>{{ $item->nomor_induk ?? '-' }}</td>
                        <td>{{ $item->nama_jenis_kesenian ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $statusClass[$item->status] ?? 'bg-secondary' }}">
                                {{ $item->status ?? '-' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
