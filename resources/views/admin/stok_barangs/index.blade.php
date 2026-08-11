@extends('layouts.admin')

@section('title', 'Kelola Stok Barang')

@section('page-title', 'Kelola Stok Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Kelola Stok Barang</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kelola Stok Barang</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.stok_barangs.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tableStok" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Minimum Stok</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokBarangs as $index => $barang)
                        <tr class="{{ $barang->isLowStock() ? 'table-warning' : '' }}">
                            <td>{{ $stokBarangs->firstItem() + $index }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>{{ $barang->minimum_stok }}</td>
                            <td>
                                @if($barang->isLowStock())
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Stok Rendah
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Aman
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.stok_barangs.edit', $barang->id_barang) }}" class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.stok_barangs.destroy', $barang->id_barang) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $stokBarangs->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tableStok').DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
    });

    $('.form-delete').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Hapus barang ini?',
            text: "Data akan dihapus secara permanen!",
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
