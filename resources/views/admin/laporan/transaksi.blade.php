@extends('layouts.admin')

@section('title', 'Laporan Transaksi')
@section('page-title', 'Laporan Transaksi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Laporan Transaksi</li>
@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Filter Laporan Transaksi</h3>
        <a href="{{ route('admin.laporan.transaksi.cetak', request()->query()) }}" target="_blank"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-pdf mr-1"></i>Cetak PDF
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.transaksi.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control"
                               value="{{ request('tanggal_mulai') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                               value="{{ request('tanggal_akhir') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="proses"  {{ request('status') === 'proses'  ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="diambil" {{ request('status') === 'diambil' ? 'selected' : '' }}>Diambil</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Pelanggan</label>
                        <select name="id_pelanggan" class="form-control">
                            <option value="">Semua Pelanggan</option>
                            @foreach ($pelanggans as $p)
                            <option value="{{ $p->id_pelanggan }}"
                                {{ request('id_pelanggan') == $p->id_pelanggan ? 'selected' : '' }}>
                                {{ $p->nama_pelanggan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('admin.laporan.transaksi.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-redo mr-1"></i>Reset
            </a>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $statistik['total_transaksi'] }}</h3><p>Total Transaksi</p></div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp {{ number_format($statistik['total_pendapatan'], 0, ',', '.') }}</h3>
                <p>Total Pendapatan</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($statistik['total_berat'], 2) }} Kg</h3>
                <p>Total Berat</p>
            </div>
            <div class="icon"><i class="fas fa-weight"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $statistik['status_proses'] }}</h3><p>Status Proses</p></div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Data Laporan Transaksi</h3>
    </div>
    <div class="card-body">
        <table id="tableLaporan" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>No. Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Pewangi</th>
                    <th>Tgl Masuk</th>
                    <th>Tgl Selesai</th>
                    <th>Berat (Kg)</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>TRX-{{ str_pad($trx->id_transaksi, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>{{ $trx->pewangi->nama_barang ?? '-' }}</td>
                    <td>{{ $trx->tanggal_masuk->format('d/m/Y') }}</td>
                    <td>{{ $trx->tanggal_selesai ? $trx->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                    <td>{{ number_format($trx->total_berat, 2) }}</td>
                    <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @switch($trx->status)
                            @case('proses') <span class="badge badge-warning">Proses</span> @break
                            @case('selesai') <span class="badge badge-success">Selesai</span> @break
                            @default <span class="badge badge-info">Diambil</span>
                        @endswitch
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted">Tidak ada data transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#tableLaporan').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        order: [[4, 'desc']],
    });
});
</script>
@endpush
