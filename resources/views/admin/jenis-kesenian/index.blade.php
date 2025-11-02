{{-- resources/views/admin/jenis-kesenian/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Jenis Kesenian')
@section('page-title', 'Data Jenis Kesenian')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Data Jenis Kesenian</h3>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalJenisKesenian" onclick="resetModal()">
                    <i class="fas fa-plus me-2"></i>Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td width="1%">No</td>
                            <td>Jenis Kesenian</td>
                            <td>Sub Kesenian</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataJenisKesenian as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->nama }}</strong>
                            </td>
                            <td>-- PARENT --</td>
                            <td>
                                <button class="btn text-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalJenisKesenian"
                                        onclick="editJenisKesenian({{ $item->id }}, '{{ $item->nama }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.jenis-kesenian.destroy', $item->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn text-danger"
                                            {{ $item->sub->count() > 0 ? 'disabled' : '' }}
                                            onclick="return confirm('Hapus jenis kesenian {{ $item->nama }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @foreach($item->sub as $subItem)
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $subItem->nama }}</td>
                            <td>
                                <button class="btn text-info mr-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalJenisKesenian"
                                        onclick="editSubJenisKesenian({{ $subItem->id }}, '{{ $subItem->nama }}', {{ $item->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.jenis-kesenian.destroy', $subItem->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn text-danger"
                                            onclick="return confirm('Hapus sub jenis kesenian {{ $subItem->nama }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data jenis kesenian</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalJenisKesenian" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="jenisKesenianForm" method="POST">
                @csrf
                <div id="formMethod"></div>

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Jenis Kesenian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group mb-3">
                                <label for="nama" class="form-label">
                                    Jenis Kesenian <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="nama" name="nama" required
                                       placeholder="Masukkan nama jenis kesenian">
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="parent" class="form-label">
                                    Sub Kesenian
                                </label>
                                <select class="form-control" id="parent" name="parent">
                                    <option value="">Parent</option>
                                    @foreach($parentJenisKesenian as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->nama }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Kosongkan jika ini adalah jenis utama</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-info">SAVE</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function resetModal() {
        document.getElementById('jenisKesenianForm').reset();
        document.getElementById('jenisKesenianForm').action = "{{ route('admin.jenis-kesenian.store') }}";
        document.getElementById('formMethod').innerHTML = '';
        document.getElementById('modalTitle').textContent = 'Tambah Jenis Kesenian';
        document.getElementById('parent').value = '';
    }

    function editJenisKesenian(id, nama) {
        resetModal();
        document.getElementById('jenisKesenianForm').action = "{{ url('admin/jenis-kesenian') }}/" + id;
        document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').textContent = 'Edit Jenis Kesenian';
        document.getElementById('nama').value = nama;
        document.getElementById('parent').value = ''; // Parent jenis utama
    }

    function editSubJenisKesenian(id, nama, parentId) {
        resetModal();
        document.getElementById('jenisKesenianForm').action = "{{ url('admin/jenis-kesenian') }}/" + id;
        document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('modalTitle').textContent = 'Edit Sub Jenis Kesenian';
        document.getElementById('nama').value = nama;
        document.getElementById('parent').value = parentId;
    }

    // Reset modal ketika ditutup
    document.getElementById('modalJenisKesenian').addEventListener('hidden.bs.modal', function () {
        resetModal();
    });
</script>
@endpush
