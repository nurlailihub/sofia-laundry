@extends('layouts.admin')

@section('title', 'Kelola Pengambilan')

@section('page-title', 'Kelola Pengambilan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Kelola Pengambilan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kelola Pengambilan</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.pengembalians.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Pengambilan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tablePengambilan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Pelanggan</th>
                            <th>ID Reservasi</th>
                            <th>Tanggal Pengambilan</th>
                            <th>Total Berat</th>
                            <th>Tagihan</th>
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Notifikasi</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengembalians as $index => $pengembalian)
                        <tr>
                            <td>{{ $pengembalians->firstItem() + $index }}</td>
                            <td>{{ $pengembalian->transaksi->pelanggan->nama_pelanggan ?? '-' }}</td>
                            <td>
                                @if($pengembalian->booking)
                                    <span class="badge badge-info">{{ $pengembalian->booking->kode_reservasi ?? '#' . $pengembalian->id_booking }}</span>
                                @elseif($pengembalian->transaksi->booking)
                                    <span class="badge badge-info">{{ $pengembalian->transaksi->booking->kode_reservasi ?? '#' . $pengembalian->transaksi->id_booking }}</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($pengembalian->transaksi->total_berat, 2) }} Kg</td>
                            <td>
                                @php
                                    $totalTagihan = $pengembalian->transaksi->total_harga + ($pengembalian->transaksi->booking?->biaya_antar_jemput ?? 0);
                                @endphp
                                <strong class="text-primary">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong>
                                @if($pengembalian->transaksi->booking?->biaya_antar_jemput)
                                    <br><small class="text-muted">
                                        + Jemput: Rp {{ number_format($pengembalian->transaksi->booking->biaya_antar_jemput, 0, ',', '.') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if ($pengembalian->transaksi->pembayaran)
                                    @if ($pengembalian->transaksi->pembayaran->status_bayar === 'lunas')
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Lunas</span>
                                    @else
                                        <span class="badge badge-warning">Belum Lunas</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">Belum Dicatat</span>
                                @endif
                            </td>
                            <td>
                                @if($pengembalian->status_pengembalian == 'siap_diambil')
                                    <span class="badge badge-success">Siap Diambil</span>
                                @else
                                    <span class="badge badge-info">Sudah Diambil</span>
                                @endif
                            </td>
                            <td>
                                @if($pengembalian->notifikasi_terkirim)
                                    <span class="badge badge-success" title="Terkirim: {{ $pengembalian->tanggal_notifikasi ? \Carbon\Carbon::parse($pengembalian->tanggal_notifikasi)->format('d/m/Y H:i') : '-' }}">
                                        <i class="fas fa-check"></i> Terkirim
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-times"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm btn-resend"
                                    data-id="{{ $pengembalian->id_pengembalian }}"
                                    title="Kirim Ulang Notifikasi">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                                <a href="{{ route('admin.pengembalians.edit', $pengembalian->id_pengembalian) }}" class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pengembalians.destroy', $pengembalian->id_pengembalian) }}" method="POST" class="d-inline form-delete">
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tablePengambilan').DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
    });

    $('.form-delete').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Hapus data ini?',
            text: "Data pengambilan akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });

    $('.btn-resend').on('click', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Kirim Notifikasi WhatsApp?',
            text: "Notifikasi akan dikirim ke pelanggan",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#25D366',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fab fa-whatsapp"></i> Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/pengembalians') }}/" + id + "/resend",
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire({
                            icon: response.success ? 'success' : 'error',
                            title: response.success ? 'Berhasil!' : 'Gagal!',
                            text: response.message,
                            timer: 3000
                        }).then(function() {
                            if (response.success) location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat mengirim notifikasi' });
                    }
                });
            }
        });
    });
});
</script>
@endpush
