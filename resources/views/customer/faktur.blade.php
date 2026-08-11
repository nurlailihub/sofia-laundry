@extends('layouts.customer')

@section('title', 'Faktur Pembayaran')
@section('page-title', 'Faktur Pembayaran')

@push('styles')
<style>
.faktur-wrap {
    max-width: 640px;
    margin: 0 auto;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.10);
}
.faktur-header {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    color: white;
    padding: 1.75rem 2rem 1.5rem;
}
.faktur-body  { padding: 1.75rem 2rem; }
.faktur-foot  { padding: 1rem 2rem; background: #f8fafc; border-top: 1px solid #e5e7eb; }
.row-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .45rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: .9rem;
}
.row-item:last-child { border-bottom: none; }
.status-pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .9rem; border-radius: 20px; font-size: .78rem; font-weight: 700;
}
.pill-lunas  { background: #d1fae5; color: #065f46; }
.pill-belum  { background: #fef3c7; color: #92400e; }
.watermark-wrap { position: relative; }
.watermark-wrap::after {
    content: 'LUNAS';
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%) rotate(-28deg);
    font-size: 5.5rem; font-weight: 900;
    color: rgba(16,185,129,.09); pointer-events: none; letter-spacing: 10px;
    white-space: nowrap;
}
</style>
@endpush

@section('content')

<div class="mb-3 d-flex gap-2">
    <a href="{{ route('customer.transaksi.detail', $pembayaran->transaksi->id_transaksi) }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
    <a href="{{ route('customer.transaksi.faktur.cetak', $pembayaran->transaksi->id_transaksi) }}"
       target="_blank" class="btn btn-dark btn-sm">
        <i class="fas fa-print me-1"></i>Cetak Struk
    </a>
</div>

<div class="faktur-wrap {{ $pembayaran->status_bayar === 'lunas' ? 'watermark-wrap' : '' }}">

    <div class="faktur-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div style="font-size:1.3rem;font-weight:800;">
                    <i class="fas fa-tshirt me-2"></i>Sofia Laundry
                </div>
                <div style="opacity:.75;font-size:.82rem;margin-top:.2rem;">Jl. Contoh No. 123, Kota Anda</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:.72rem;opacity:.7;text-transform:uppercase;letter-spacing:.5px;">No. Faktur</div>
                <div style="font-size:1rem;font-weight:700;">{{ $pembayaran->nomor_faktur }}</div>
                <div style="font-size:.78rem;opacity:.7;margin-top:.2rem;">
                    {{ $pembayaran->tanggal_bayar->format('d F Y, H:i') }}
                </div>
            </div>
        </div>
        <div class="mt-3 pt-2" style="border-top:1px solid rgba(255,255,255,.2);">
            @php
                $isJemput = $pembayaran->transaksi->booking && in_array($pembayaran->transaksi->booking->tipe_antar_jemput, ['pickup', 'both']);
            @endphp
            @if ($isJemput)
            <span class="status-pill" style="background:rgba(255,255,255,.2);color:white;">
                <i class="fas fa-truck-pickup"></i> FAKTUR PENJEMPUTAN
            </span>
            @else
            <span class="{{ $pembayaran->status_bayar === 'lunas' ? 'pill-lunas' : 'pill-belum' }} status-pill"
                  style="background:rgba(255,255,255,.2);color:white;">
                @if ($pembayaran->status_bayar === 'lunas')
                    <i class="fas fa-check-circle"></i> LUNAS
                @else
                    <i class="fas fa-clock"></i> MENUNGGU PEMBAYARAN
                @endif
            </span>
            @endif
        </div>
    </div>

    <div class="faktur-body">

        <div class="row mb-4">
            <div class="col-6">
                <div class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Tagihan Kepada</div>
                <div class="fw-bold">{{ $pelanggan->nama_pelanggan }}</div>
                <div class="text-muted small">{{ $pelanggan->no_hp }}</div>
            </div>
            <div class="col-6 text-end">
                <div class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Info Transaksi</div>
                <div class="small fw-semibold">#{{ str_pad($pembayaran->transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="text-muted small">Masuk: {{ $pembayaran->transaksi->tanggal_masuk->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="text-muted mb-2" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;font-weight:600;">
            Rincian Layanan
        </div>
        @foreach ($pembayaran->transaksi->detailTransaksi as $d)
        <div class="row-item">
            <span>{{ $d->layanan->nama_layanan ?? '-' }} <span class="text-muted small">({{ $d->berat }} kg)</span></span>
            <span class="fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach

        @if ($pembayaran->transaksi->pewangi)
        <div class="row-item">
            <span class="text-muted"><i class="fas fa-spray-can me-1"></i>Pewangi: {{ $pembayaran->transaksi->pewangi->nama_barang }}</span>
            <span class="text-muted small">Termasuk</span>
        </div>
        @endif

        @if ($pembayaran->transaksi->booking?->biaya_antar_jemput)
        <div class="row-item">
            <span>
                <i class="fas fa-truck me-1 text-muted"></i>
                Antar/Jemput
                <span class="text-muted small">
                    ({{ ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'][$pembayaran->transaksi->booking->tipe_antar_jemput] ?? '' }})
                </span>
            </span>
            <span class="fw-semibold">Rp {{ number_format($pembayaran->transaksi->booking->biaya_antar_jemput, 0, ',', '.') }}</span>
        </div>
        @endif

        @php
            $tagihan   = $pembayaran->transaksi->total_harga + ($pembayaran->transaksi->booking?->biaya_antar_jemput ?? 0);
            $kembalian = $pembayaran->jumlah_bayar - $tagihan;
        @endphp

        <div class="mt-3 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e5e7eb;">
            <div class="row-item">
                <span class="text-muted">Total Tagihan</span>
                <span class="fw-semibold">Rp {{ number_format($tagihan, 0, ',', '.') }}</span>
            </div>
            <div class="row-item">
                <span class="text-muted">Jumlah Dibayar</span>
                <span class="fw-semibold">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
            @if ($kembalian > 0)
            <div class="row-item" style="color:#10b981;">
                <span>Kembalian</span>
                <span class="fw-semibold">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="d-flex justify-content-between align-items-center pt-2 mt-1" style="border-top:1.5px solid #e5e7eb;">
                <span class="fw-bold" style="font-size:.95rem;">Total Dibayar</span>
                <span class="fw-bold text-primary" style="font-size:1.35rem;">
                    Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="mt-3 p-3 rounded-3 d-flex justify-content-between align-items-center"
             style="background:#eff6ff;border:1px solid #dbeafe;">
            <div>
                @php $icons = \App\Models\Pembayaran::$metodeIcons; $labels = \App\Models\Pembayaran::$metodeLabels; @endphp
                <div class="text-muted small mb-1">Metode Pembayaran</div>
                <div class="fw-bold">
                    <i class="{{ $icons[$pembayaran->metode_bayar] ?? 'fas fa-money-bill' }} me-2 text-primary"></i>
                    {{ $labels[$pembayaran->metode_bayar] ?? $pembayaran->metode_bayar }}
                </div>
                @if ($pembayaran->nomor_referensi)
                <div class="text-muted small mt-1">Ref: {{ $pembayaran->nomor_referensi }}</div>
                @endif
            </div>
            <span class="{{ $pembayaran->status_bayar === 'lunas' ? 'pill-lunas' : 'pill-belum' }} status-pill">
                @if ($pembayaran->status_bayar === 'lunas')
                    <i class="fas fa-check-circle"></i> Lunas
                @else
                    <i class="fas fa-clock"></i> Belum Lunas
                @endif
            </span>
        </div>

        @if ($pembayaran->catatan)
        <div class="mt-3 text-muted small">
            <i class="fas fa-comment me-1"></i>{{ $pembayaran->catatan }}
        </div>
        @endif

    </div>

    <div class="faktur-foot d-flex justify-content-between align-items-center">
        <div class="text-muted" style="font-size:.75rem;">{{ now()->format('d F Y, H:i') }}</div>
        <div class="text-muted" style="font-size:.75rem;">Terima kasih 🙏</div>
    </div>
</div>

@endsection

@push('styles')
<style>
@media print {
    .sidebar, .top-bar, .btn, .mb-3 { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; }
    .faktur-wrap { box-shadow: none; border-radius: 0; }
}
</style>
@endpush
