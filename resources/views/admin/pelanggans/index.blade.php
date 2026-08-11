@extends('layouts.admin')

@section('title', 'Kelola Data Pelanggan')

@section('page-title', 'Kelola Data Pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Kelola Data Pelanggan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kelola Data Pelanggan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.pelanggans.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Pelanggan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tablePelanggan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>ID Pelanggan</th>
                            <th>Nama Pelanggan</th>
                            <th>No. HP/WhatsApp</th>
                            <th>Alamat</th>
                            <th>Akun</th>
                            <th>Tanggal Daftar</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggans as $index => $pelanggan)
                        <tr>
                            <td>{{ $pelanggans->firstItem() + $index }}</td>
                            <td><span class="badge badge-primary">#{{ $pelanggan->id_pelanggan }}</span></td>
                            <td>{{ $pelanggan->nama_pelanggan }}</td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pelanggan->no_hp) }}" target="_blank" class="text-success">
                                    <i class="fab fa-whatsapp"></i> {{ $pelanggan->no_hp }}
                                </a>
                            </td>
                            <td>{{ $pelanggan->alamat }}</td>
                            <td>
                                @if ($pelanggan->user)
                                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>{{ $pelanggan->user->username }}</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times mr-1"></i>Belum punya akun</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($pelanggan->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.pelanggans.edit', $pelanggan->id_pelanggan) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pelanggans.destroy', $pelanggan->id_pelanggan) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tablePelanggan').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });
});

function confirmDelete(form) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pelanggan akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
</script>
@endpush
