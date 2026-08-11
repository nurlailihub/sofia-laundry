@extends('layouts.admin')

@section('title', 'Transaksi Laundry')
@section('page-title', 'Transaksi Laundry')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Transaksi Laundry</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Transaksi Laundry</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.transaksis.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Transaksi
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="searchPelanggan" class="form-control" placeholder="Cari ID atau Nama Pelanggan...">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                            <option value="diambil">Diambil</option>
                        </select>
                    </div>
                </div>

                <table id="tableTransaksi" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4%">No</th>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Antar/Jemput</th>
                            <th>Tgl Masuk</th>
                            <th>Est. Selesai</th>
                            <th>Berat (Kg)</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th width="14%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksis as $index => $transaksi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge badge-primary">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
                                @if($transaksi->booking)
                                <br><small class="text-muted">{{ $transaksi->booking->kode_reservasi }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</div>
                                <small class="text-muted">#{{ $transaksi->id_pelanggan }}</small>
                            </td>
                            {{-- Poin 4: Info antar/jemput di transaksi --}}
                            <td>
                                @php
                                    $tipe = $transaksi->tipe_antar ?? 'none';
                                    $tipeMap = [
                                        'none'     => ['label' => 'Sendiri', 'color' => 'secondary', 'icon' => 'fas fa-walking'],
                                        'pickup'   => ['label' => 'Dijemput', 'color' => 'info', 'icon' => 'fas fa-hand-holding'],
                                        'delivery' => ['label' => 'Diantar', 'color' => 'primary', 'icon' => 'fas fa-truck'],
                                        'both'     => ['label' => 'Jemput&Antar', 'color' => 'purple', 'icon' => 'fas fa-exchange-alt'],
                                    ];
                                    $tm = $tipeMap[$tipe] ?? $tipeMap['none'];
                                @endphp
                                <span class="badge badge-{{ $tm['color'] }}">
                                    <i class="{{ $tm['icon'] }} mr-1"></i>{{ $tm['label'] }}
                                </span>
                                @if($tipe !== 'none' && $transaksi->biaya_antar > 0)
                                <br><small class="text-success">Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td>{{ $transaksi->tanggal_selesai ? \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                            <td>{{ number_format($transaksi->total_berat, 2) }} Kg</td>
                            <td>
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                @if($transaksi->biaya_antar > 0)
                                <br><small class="text-muted">+Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }} antar</small>
                                @endif
                            </td>
                            <td>
                                @if($transaksi->status == 'proses')
                                    <span class="badge badge-warning">Proses</span>
                                @elseif($transaksi->status == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @else
                                    <span class="badge badge-info">Diambil</span>
                                @endif
                            </td>
                            <td>
                                @if ($transaksi->pembayaran)
                                    @if ($transaksi->pembayaran->status_bayar === 'lunas')
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Lunas</span>
                                        <a href="{{ route('admin.pembayarans.faktur', $transaksi->pembayaran->id_pembayaran) }}"
                                           class="badge badge-light border ml-1" title="Lihat Faktur Bayar">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    @else
                                        <span class="badge badge-warning">Belum Lunas</span>
                                        <a href="{{ route('admin.pembayarans.create', $transaksi->id_transaksi) }}"
                                           class="badge badge-warning border ml-1" title="Lanjut Bayar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('admin.pembayarans.create', $transaksi->id_transaksi) }}"
                                       class="badge badge-secondary">
                                        <i class="fas fa-plus mr-1"></i>Catat
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{-- Poin 2: Cetak Faktur --}}
                                <a href="{{ route('admin.transaksis.faktur', $transaksi->id_transaksi) }}"
                                   class="btn btn-success btn-sm mb-1" title="Cetak Faktur">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="{{ route('admin.transaksis.edit', $transaksi->id_transaksi) }}"
                                   class="btn btn-info btn-sm mb-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.transaksis.destroy', $transaksi->id_transaksi) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Hapus">
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
    var table = $('#tableTransaksi').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        columnDefs: [{ orderable: false, targets: [10] }],
    });

    $('#searchPelanggan').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('#filterStatus').on('change', function() {
        table.column(8).search($(this).val()).draw();
    });

    $('.form-delete').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Hapus transaksi ini?',
            text: 'Data akan dihapus permanen!',
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
