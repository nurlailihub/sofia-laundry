@extends('layouts.admin')

@section('title', 'Faktur Transaksi')
@section('page-title', 'Faktur Transaksi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.transaksis.index') }}">Transaksi</a></li>
<li class="breadcrumb-item active">Faktur</li>
@endsection

@push('styles')
<style>
.faktur-wrapper { max-width: 680px; margin: 0 auto; }
.faktur-header { background: linear-gradient(135deg, #005F73, #2BB1B1); color: #fff; border-radius: 12px 12px 0 0; padding: 1.5rem 2rem; }
.faktur-body { background: #fff; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px; padding: 1.5rem 2rem; }
.row-info td { padding: .3rem .5rem; font-size: .875rem; }
.row-info td:first-child { color: #6b7280; width: 140px; }
table.detail-table th { background: #f1f5f9; font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; }
table.detail-table td, table.detail-table th { padding: .5rem .75rem; }
.total-final { background: #f0fdf4; border: 2px solid #6ee7b7; border-radius: 8px; padding: .75rem 1rem; }
.badge-tipe { font-size: .75rem; padding: .35rem .75rem; border-radius: 9999px; }
</style>
@endpush

@section('content')
<div class="faktur-wrapper">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.transaksis.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <div>
            <a href="{{ route('admin.transaksis.cetak', $transaksi->id_transaksi) }}"
               class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print mr-1"></i> Cetak / Download PDF
            </a>
        </div>
    </div>

    <div class="faktur-wrapper shadow-sm">
        <div class="faktur-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:1.4rem;font-weight:800;letter-spacing:-.5px;">Sofia Laundry</div>
                    <div style="opacity:.8;font-size:.85rem;">Jl. Contoh No. 1, Kota</div>
                </div>
                <div class="text-right">
                    <div style="font-size:.75rem;opacity:.7;text-transform:uppercase;">Faktur Transaksi</div>
                    <div style="font-size:1.2rem;font-weight:800;">
                        #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    @if($transaksi->booking)
                    <div style="font-size:.8rem;opacity:.8;">{{ $transaksi->booking->kode_reservasi }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="faktur-body">

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="font-weight-bold text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Data Pelanggan</div>
                    <table class="row-info">
                        <tr><td>Nama</td><td class="font-weight-bold">{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</td></tr>
                        <tr><td>No. HP</td><td>{{ $transaksi->pelanggan->no_hp ?? '-' }}</td></tr>
                        <tr><td>Alamat</td><td>{{ $transaksi->pelanggan->alamat ?? '-' }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="font-weight-bold text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Info Transaksi</div>
                    <table class="row-info">
                        <tr><td>Tgl Masuk</td><td class="font-weight-bold">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y H:i') }}</td></tr>
                        <tr>
                            <td>Est. Selesai</td>
                            <td>{{ $transaksi->tanggal_selesai ? \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                        </tr>
                        <tr><td>Petugas</td><td>{{ $transaksi->user->nama_user ?? '-' }}</td></tr>
                        @if($transaksi->pewangi)
                        <tr><td>Pewangi</td><td>{{ $transaksi->pewangi->nama_barang }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Info Antar/Jemput --}}
            @php
                $tipeAntar = $transaksi->tipe_antar ?? 'none';
                $tipeAntarMap = [
                    'none'     => ['label' => 'Antar Sendiri', 'color' => '#6b7280'],
                    'pickup'   => ['label' => 'Dijemput Admin', 'color' => '#0891b2'],
                    'delivery' => ['label' => 'Diantar Admin',  'color' => '#2563eb'],
                    'both'     => ['label' => 'Jemput & Antar', 'color' => '#7c3aed'],
                ];
                $tm = $tipeAntarMap[$tipeAntar] ?? $tipeAntarMap['none'];
            @endphp
            @if($tipeAntar !== 'none')
            <div class="mb-3 p-3 rounded" style="background:#f0f9ff;border:1px solid #bae6fd;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="font-weight-bold" style="color:{{ $tm['color'] }};">
                            <i class="fas fa-truck mr-1"></i>{{ $tm['label'] }}
                        </span>
                        @if($transaksi->booking)
                            @if($transaksi->booking->alamat_jemput)
                            <div class="small text-muted mt-1"><i class="fas fa-map-marker-alt mr-1"></i>Jemput: {{ $transaksi->booking->alamat_jemput }}</div>
                            @endif
                            @if($transaksi->booking->alamat_antar)
                            <div class="small text-muted"><i class="fas fa-map-marker-alt mr-1"></i>Antar: {{ $transaksi->booking->alamat_antar }}</div>
                            @endif
                        @endif
                    </div>
                    @if($transaksi->biaya_antar > 0)
                    <div class="text-right">
                        <div class="text-muted small">Biaya Antar/Jemput</div>
                        <div class="font-weight-bold">Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Detail Layanan --}}
            <table class="table detail-table mb-0">
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th class="text-center">Berat (kg)</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->detailTransaksi as $d)
                    <tr>
                        <td>{{ $d->layanan->nama_layanan ?? '-' }}</td>
                        <td class="text-center">{{ number_format($d->berat, 2) }}</td>
                        <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3 px-2">
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Total Berat</span>
                    <span class="font-weight-bold">{{ number_format($transaksi->total_berat, 2) }} kg</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Subtotal Layanan</span>
                    <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                @if($transaksi->biaya_antar > 0)
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Biaya Antar/Jemput</span>
                    <span>Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-final d-flex justify-content-between align-items-center mt-2">
                    <span class="font-weight-bold">TOTAL TAGIHAN</span>
                    <span style="font-size:1.3rem;font-weight:800;color:#059669;">
                        Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Status Pembayaran --}}
            @if($transaksi->pembayaran)
            <div class="mt-4 p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">
                <div class="font-weight-bold text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Status Pembayaran</div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge badge-{{ $transaksi->pembayaran->status_bayar === 'lunas' ? 'success' : 'warning' }} badge-lg">
                            {{ $transaksi->pembayaran->status_bayar === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                        </span>
                        <span class="ml-2 text-muted small">{{ strtoupper($transaksi->pembayaran->metode_bayar ?? '-') }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-muted small">Dibayar</div>
                        <div class="font-weight-bold text-success">Rp {{ number_format($transaksi->pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            @else
            <div class="mt-4 text-center p-3 rounded" style="background:#fef3c7;border:1px solid #fde68a;">
                <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                <span class="text-warning font-weight-bold">Belum Ada Pembayaran</span>
                <div class="mt-2">
                    <a href="{{ route('admin.pembayarans.create', $transaksi->id_transaksi) }}"
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-plus mr-1"></i>Catat Pembayaran
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
