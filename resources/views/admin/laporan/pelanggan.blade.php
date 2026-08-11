@extends('layouts.admin')

@section('title', 'Laporan Pelanggan')
@section('page-title', 'Laporan Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Laporan Pelanggan</li>
@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Filter Laporan Pelanggan</h3>
        <a href="{{ route('admin.laporan.pelanggan.cetak', request()->query()) }}" target="_blank"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-pdf mr-1"></i>Cetak PDF
        </a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.pelanggan.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control"
                               value="{{ request('nama_pelanggan') }}" placeholder="Cari nama...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control"
                               value="{{ request('tanggal_mulai') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                               value="{{ request('tanggal_akhir') }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('admin.laporan.pelanggan.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-redo mr-1"></i>Reset
            </a>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $statistik['total_pelanggan'] }}</h3><p>Total Pelanggan</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $statistik['total_transaksi_keseluruhan'] }}</h3><p>Total Transaksi</p></div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Rp {{ number_format($statistik['total_pendapatan_keseluruhan'], 0, ',', '.') }}</h3>
                <p>Total Pendapatan</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3>{{ $statistik['pelanggan_aktif'] }}</h3><p>Pelanggan Aktif</p></div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Data Laporan Pelanggan</h3>
    </div>
    <div class="card-body">
        <table id="tableLaporan" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Pelanggan</th>
                    <th>No. HP / WhatsApp</th>
                    <th>Alamat</th>
                    <th>Total Transaksi</th>
                    <th>Total Pendapatan</th>
                    <th>Transaksi Terakhir</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelanggans as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->nama_pelanggan }}</td>
                    <td>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->no_hp) }}"
                           target="_blank" class="text-success">
                            <i class="fab fa-whatsapp mr-1"></i>{{ $p->no_hp }}
                        </a>
                    </td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->total_transaksi }}</td>
                    <td>Rp {{ number_format($p->total_pendapatan, 0, ',', '.') }}</td>
                    <td>{{ $p->transaksi_terakhir ? \Carbon\Carbon::parse($p->transaksi_terakhir)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if ($p->total_transaksi > 0)
                        <span class="badge badge-success">Aktif</span>
                        @else
                        <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted">Tidak ada data pelanggan</td></tr>
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
