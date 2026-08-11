@extends('layouts.admin')

@section('title', 'Catat Pembayaran')
@section('page-title', 'Catat Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Catat Pembayaran</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Riwayat Catat Pembayaran</h3>
        <a href="{{ route('admin.transaksis.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i>Catat Pembayaran Baru
        </a>
    </div>
    <div class="card-body">
        <table id="tablePembayaran" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>No. Faktur</th>
                    <th>Pelanggan</th>
                    <th>Tanggal Bayar</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayarans as $i => $p)
                <tr>
                    <td>{{ $pembayarans->firstItem() + $i }}</td>
                    <td><strong>{{ $p->nomor_faktur }}</strong></td>
                    <td>{{ $p->transaksi->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $p->tanggal_bayar->format('d/m/Y H:i') }}</td>
                    <td>
                        @php $icons = \App\Models\Pembayaran::$metodeIcons; $labels = \App\Models\Pembayaran::$metodeLabels; @endphp
                        <i class="{{ $icons[$p->metode_bayar] ?? 'fas fa-money-bill' }} mr-1"></i>
                        {{ $labels[$p->metode_bayar] ?? $p->metode_bayar }}
                    </td>
                    <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>
                        @if($p->status_bayar === 'lunas')
                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Lunas</span>
                        @else
                        <span class="badge badge-warning">Belum Lunas</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.pembayarans.faktur', $p->id_pembayaran) }}" class="btn btn-info btn-sm" title="Lihat Faktur">
                            <i class="fas fa-file-invoice"></i>
                        </a>
                        <a href="{{ route('admin.pembayarans.cetak', $p->id_pembayaran) }}" class="btn btn-secondary btn-sm" title="Cetak PDF" target="_blank">
                            <i class="fas fa-print"></i>
                        </a>
                        <form action="{{ route('admin.pembayarans.destroy', $p->id_pembayaran) }}" method="POST" class="d-inline form-delete">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{{ $pembayarans->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#tablePembayaran').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }, paging: false });
    $('.form-delete').on('submit', function (e) {
        e.preventDefault();
        const f = this;
        Swal.fire({ title: 'Hapus pembayaran?', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then(r => { if (r.isConfirmed) f.submit(); });
    });
});
</script>
@endpush
