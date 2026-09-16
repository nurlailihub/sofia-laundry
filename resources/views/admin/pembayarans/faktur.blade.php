@extends('layouts.admin')

@section('title', 'Faktur Pembayaran')
@section('page-title', 'Faktur Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.pembayarans.index') }}">Catat Pembayaran</a></li>
<li class="breadcrumb-item active">Faktur</li>
@endsection

@push('styles')
<style>
/* Kontainer struk */
.struk-preview {
    width: 340px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #d1d5db;
    box-shadow: 2px 4px 16px rgba(0,0,0,.12);
    padding: 18px 20px 22px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 12.5px;
    color: #000;
    position: relative;
}
.struk-preview::before {
    content: '';
    display: block;
    height: 6px;
    background: repeating-linear-gradient(90deg, #000 0, #000 8px, transparent 8px, transparent 14px);
    margin-bottom: 14px;
}
.struk-preview::after {
    content: '';
    display: block;
    height: 6px;
    background: repeating-linear-gradient(90deg, #000 0, #000 8px, transparent 8px, transparent 14px);
    margin-top: 14px;
}
.s-store { text-align: center; font-size: 17px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }
.s-sub   { text-align: center; font-size: 10.5px; color: #374151; margin-top: 1px; }
.s-line-solid  { border: none; border-top: 1px solid #000; margin: 7px 0; }
.s-line-dash   { border: none; border-top: 1px dashed #555; margin: 7px 0; }
.s-line-double { border: none; border-top: 3px double #000; margin: 7px 0; }
.s-row {
    display: flex; justify-content: space-between;
    align-items: baseline; margin: 2.5px 0; font-size: 11.5px;
}
.s-row .s-lbl { flex: 1; }
.s-row .s-val { text-align: right; white-space: nowrap; margin-left: 6px; }
.s-indent { padding-left: 12px; font-size: 10.5px; color: #555; margin: 1px 0; }
.s-section-title { text-align: center; font-size: 10.5px; font-weight: bold; margin: 5px 0; }
.s-total-big { display: flex; justify-content: space-between; font-size: 16px; font-weight: 900; margin: 5px 0; }
.s-stamp-wrap { text-align: center; margin: 10px 0; }
.s-stamp {
    display: inline-block;
    border: 3px solid #000;
    padding: 3px 14px;
    font-size: 22px;
    font-weight: 900;
    letter-spacing: 6px;
    transform: rotate(-12deg);
    opacity: .18;
}
.s-footer { text-align: center; font-size: 10px; color: #555; margin-top: 3px; }
</style>
@endpush

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if (session('wa_success'))
<div class="alert alert-info alert-dismissible fade show mb-3">
    <i class="fab fa-whatsapp mr-2" style="color:#25d366;"></i>{{ session('wa_success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

@if (session('wa_error'))
<div class="alert alert-warning alert-dismissible fade show mb-3">
    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('wa_error') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

{{-- Peringatan jika WA_PUBLIC_URL masih lokal --}}
@php
    $waPublicUrl = env('WA_PUBLIC_URL', config('app.url'));
    $isLocalUrl  = str_contains($waPublicUrl, '127.0.0.1')
                || str_contains($waPublicUrl, 'localhost')
                || str_ends_with($waPublicUrl, '.test')
                || str_ends_with($waPublicUrl, '.local');
@endphp
@if($isLocalUrl)
<div class="alert alert-warning mb-3" style="max-width:620px;margin-left:auto;margin-right:auto;">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Link struk belum bisa diakses dari HP pelanggan!</strong><br>
    <small>
        URL saat ini (<code>{{ $waPublicUrl }}</code>) hanya bisa dibuka dari komputer lokal.
        Agar link bisa diklik pelanggan via WA, update <code>WA_PUBLIC_URL</code> di <code>.env</code>
        dengan domain publik atau ngrok tunnel.<br><br>
        <strong>Cara cepat pakai ngrok:</strong><br>
        1. Download ngrok di <a href="https://ngrok.com/download" target="_blank">ngrok.com/download</a><br>
        2. Jalankan: <code>ngrok http 80</code> (atau port Laravel kamu)<br>
        3. Copy URL ngrok (contoh: <code>https://abc123.ngrok-free.app</code>)<br>
        4. Update <code>WA_PUBLIC_URL=https://abc123.ngrok-free.app</code> di <code>.env</code><br>
        5. Jalankan <code>php artisan config:clear</code>
    </small>
</div>
@endif

{{-- Tombol aksi di atas --}}
<div class="d-flex justify-content-center mb-3" style="gap:.75rem;flex-wrap:wrap;">
    <a href="{{ route('admin.pembayarans.cetak', $pembayaran->id_pembayaran) }}" target="_blank"
       class="btn btn-dark px-4">
        <i class="fas fa-print mr-2"></i>Cetak Struk
    </a>

    {{-- Tombol kirim WA --}}
    @if($pembayaran->transaksi->pelanggan->no_hp ?? false)
    <form action="{{ route('admin.pembayarans.kirim-wa', $pembayaran->id_pembayaran) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success px-4"
                onclick="return confirm('Kirim struk ke WhatsApp {{ $pembayaran->transaksi->pelanggan->no_hp ?? '' }}?')"
                @if($isLocalUrl) title="Peringatan: link tidak bisa dibuka dari HP pelanggan (URL lokal)" @endif>
            <i class="fab fa-whatsapp mr-2"></i>Kirim ke WA
            @if($isLocalUrl)<i class="fas fa-exclamation-circle ml-1 text-warning"></i>@endif
        </button>
    </form>
    @endif

    @if ($pembayaran->status_bayar !== 'lunas')
    <a href="{{ route('admin.pembayarans.create', $pembayaran->transaksi->id_transaksi) }}"
       class="btn btn-warning px-4">
        <i class="fas fa-plus mr-2"></i>Tambah Pembayaran
    </a>
    @endif
    <a href="{{ route('admin.transaksis.index') }}" class="btn btn-outline-secondary px-4">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

{{-- Info link struk digital --}}
<div class="mb-4 p-3 rounded d-flex align-items-center justify-content-between"
     style="max-width:620px;margin:0 auto;background:#f0fdf4;border:1px solid #6ee7b7;gap:12px;">
    <div style="flex:1;min-width:0;">
        <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#059669;margin-bottom:3px;">
            🔗 Link Struk Digital (untuk dibagikan ke pelanggan)
        </div>
        <input type="text" id="linkStrukInput" value="{{ $linkStruk }}" readonly
               class="form-control form-control-sm"
               style="font-size:.78rem;font-family:monospace;background:#fff;color:#374151;border-color:#a7f3d0;">
    </div>
    <div style="flex-shrink:0;">
        <button onclick="copyLink()" class="btn btn-sm btn-outline-success" id="btnCopy" title="Salin link">
            <i class="fas fa-copy"></i>
        </button>
        <a href="{{ $linkStruk }}" target="_blank" class="btn btn-sm btn-outline-info ml-1" title="Buka struk">
            <i class="fas fa-external-link-alt"></i>
        </a>
    </div>
</div>

{{-- Preview Struk --}}
@php
    $tipeLabel  = ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'];
    $biayaAntar = $pembayaran->transaksi->biaya_antar;
    $tipeAntar  = $pembayaran->transaksi->tipe_antar;
    $tagihan    = $pembayaran->transaksi->total_tagihan;
    $kembalian  = $pembayaran->jumlah_bayar - $tagihan;
    $labels     = \App\Models\Pembayaran::$metodeLabels;
@endphp

<div class="struk-preview">

    {{-- Header --}}
    <div class="s-store">Sofia Laundry</div>
    <div class="s-sub">Jl. Contoh No. 123, Kota Anda</div>
    <div class="s-sub">Telp: +62 812-3456-7890</div>

    <hr class="s-line-solid">

    <div class="s-row">
        <span class="s-lbl">No. Faktur</span>
        <span class="s-val" style="font-weight:700;">{{ $pembayaran->nomor_faktur }}</span>
    </div>
    <div class="s-row">
        <span class="s-lbl">Tanggal</span>
        <span class="s-val">{{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</span>
    </div>
    <div class="s-row">
        <span class="s-lbl">Kasir</span>
        <span class="s-val">{{ $pembayaran->transaksi->user->nama_user ?? '-' }}</span>
    </div>

    <hr class="s-line-dash">

    <div class="s-row">
        <span class="s-lbl">Pelanggan</span>
        <span class="s-val" style="font-weight:700;">{{ $pembayaran->transaksi->pelanggan->nama_pelanggan ?? '-' }}</span>
    </div>
    <div class="s-row">
        <span class="s-lbl">No. HP</span>
        <span class="s-val">{{ $pembayaran->transaksi->pelanggan->no_hp ?? '-' }}</span>
    </div>
    <div class="s-row">
        <span class="s-lbl">No. Transaksi</span>
        <span class="s-val">#{{ str_pad($pembayaran->transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="s-row">
        <span class="s-lbl">Tgl Masuk</span>
        <span class="s-val">{{ $pembayaran->transaksi->tanggal_masuk->format('d/m/Y') }}</span>
    </div>

    <hr class="s-line-dash">
    <div class="s-section-title">---- RINCIAN LAYANAN ----</div>

    @foreach ($pembayaran->transaksi->detailTransaksi as $d)
    <div class="s-row" style="font-weight:700;">
        <span class="s-lbl">{{ $d->layanan->nama_layanan ?? '-' }}</span>
        <span class="s-val">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
    </div>
    <div class="s-indent">
        {{ number_format($d->berat, 2, ',', '.') }} kg
        x Rp {{ number_format($d->layanan->harga_per_kg ?? 0, 0, ',', '.') }}/kg
    </div>
    @endforeach

    @if ($pembayaran->transaksi->pewangi)
    <div class="s-row">
        <span class="s-lbl">Pewangi ({{ $pembayaran->transaksi->pewangi->nama_barang }})</span>
        <span class="s-val" style="color:#555;">Termasuk</span>
    </div>
    @endif

    @if ($biayaAntar > 0)
    <div class="s-row">
        <span class="s-lbl">Antar/Jemput ({{ $tipeLabel[$tipeAntar] ?? '-' }})</span>
        <span class="s-val">Rp {{ number_format($biayaAntar, 0, ',', '.') }}</span>
    </div>
    @endif

    <hr class="s-line-dash">

    <div class="s-row">
        <span class="s-lbl">Subtotal Layanan</span>
        <span class="s-val">Rp {{ number_format($pembayaran->transaksi->total_harga, 0, ',', '.') }}</span>
    </div>
    @if ($biayaAntar > 0)
    <div class="s-row">
        <span class="s-lbl">Biaya Antar/Jemput</span>
        <span class="s-val">Rp {{ number_format($biayaAntar, 0, ',', '.') }}</span>
    </div>
    @endif

    <hr class="s-line-solid">

    <div class="s-total-big">
        <span>TOTAL</span>
        <span>Rp {{ number_format($tagihan, 0, ',', '.') }}</span>
    </div>
    <div class="s-row">
        <span class="s-lbl">Dibayar</span>
        <span class="s-val">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
    </div>
    @if ($kembalian > 0)
    <div class="s-row" style="font-weight:700;">
        <span class="s-lbl">Kembali</span>
        <span class="s-val">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
    </div>
    @elseif ($tagihan > $pembayaran->jumlah_bayar)
    <div class="s-row" style="font-weight:700;">
        <span class="s-lbl">Sisa Bayar</span>
        <span class="s-val">Rp {{ number_format($tagihan - $pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
    </div>
    @endif

    <hr class="s-line-dash">

    <div class="s-row">
        <span class="s-lbl">Metode Bayar</span>
        <span class="s-val">{{ $labels[$pembayaran->metode_bayar] ?? $pembayaran->metode_bayar }}</span>
    </div>
    @if ($pembayaran->nomor_referensi)
    <div class="s-row">
        <span class="s-lbl">No. Referensi</span>
        <span class="s-val">{{ $pembayaran->nomor_referensi }}</span>
    </div>
    @endif
    @if ($pembayaran->catatan)
    <div style="font-size:10.5px;margin-top:4px;">Ket: {{ $pembayaran->catatan }}</div>
    @endif

    <hr class="s-line-double">

    @if ($pembayaran->status_bayar === 'lunas')
    <div class="s-stamp-wrap"><span class="s-stamp">LUNAS</span></div>
    @else
    <div style="text-align:center;font-weight:700;font-size:12px;margin:8px 0;">
        *** BELUM LUNAS ***
    </div>
    @endif

    <hr class="s-line-dash">

    <div class="s-footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <div class="s-footer" style="margin-top:6px;font-weight:700;">*** Terima kasih ***</div>
    <div class="s-footer">Sofia Laundry</div>
    <div class="s-footer">Simpan struk sebagai bukti pembayaran</div>

</div>

@push('scripts')
<script>
function copyLink() {
    var input = document.getElementById('linkStrukInput');
    input.select();
    input.setSelectionRange(0, 99999);
    try {
        document.execCommand('copy');
        var btn = document.getElementById('btnCopy');
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('btn-outline-success');
        btn.classList.add('btn-success');
        setTimeout(function() {
            btn.innerHTML = '<i class="fas fa-copy"></i>';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
        }, 2000);
    } catch (e) {
        // fallback: clipboard API
        navigator.clipboard.writeText(input.value);
    }
}
</script>
@endpush

@endsection
