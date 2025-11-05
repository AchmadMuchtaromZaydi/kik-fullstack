<h6 class="mb-3">{{ $title }} ({{ $data->count() }} data)</h6>

@if ($data->isEmpty())
    <div class="alert alert-info">Tidak ada data</div>
@else
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-sm table-striped table-hover">
            <thead class="table-light sticky-top">
                <tr>
                    <th width="50">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name ?? '-' }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $user->role ?? '-' }}</span></td>
                        <td>
                            @if ($user->isActive)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
