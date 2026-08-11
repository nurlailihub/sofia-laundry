@extends('layouts.admin')

@section('title', 'Kelola Data Layanan')

@section('page-title', 'Kelola Data Layanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Kelola Data Layanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kelola Data Layanan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.layanans.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Layanan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tableLayanan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Layanan</th>
                            <th>Harga per Kg</th>
                            <th>Keterangan</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($layanans as $index => $layanan)
                        <tr>
                            <td>{{ $layanans->firstItem() + $index }}</td>
                            <td>{{ $layanan->nama_layanan }}</td>
                            <td>Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}</td>
                            <td>{{ $layanan->keterangan ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.layanans.edit', $layanan->id_layanan) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.layanans.destroy', $layanan->id_layanan) }}" method="POST" class="d-inline" onsubmit="return confirmDelete(this)">
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
    $('#tableLayanan').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });
});

function confirmDelete(form) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Layanan akan dihapus!",
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
