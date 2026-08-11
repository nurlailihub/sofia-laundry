@extends('layouts.admin')

@section('title', 'Kelola Data Pengguna')

@section('page-title', 'Kelola Data Pengguna')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Kelola Data Pengguna</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kelola Data Pengguna</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tableUser" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama User</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->nama_user }}</td>
                            <td>{{ $user->username }}</td>
                            <td>
                                @if($user->role === 'admin')
                                <span class="badge badge-primary">Admin</span>
                                @elseif($user->role === 'pimpinan')
                                <span class="badge badge-success">Pimpinan</span>
                                @else
                                <span class="badge badge-info">Customer</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id_user !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#tableUser').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }, paging: false });

    $('.form-delete').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Hapus user ini?',
            text: 'Tindakan ini tidak bisa dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});
</script>
@endpush
