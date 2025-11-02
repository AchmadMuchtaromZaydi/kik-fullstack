{{-- resources/views/admin/partials/stats/users-list.blade.php --}}
@if ($data->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role ?? 'User' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->isActive ? 'success' : 'danger' }}">
                                {{ $user->isActive ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <strong>Total: {{ $data->count() }} user</strong>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>Tidak ada data ditemukan.
    </div>
@endif
