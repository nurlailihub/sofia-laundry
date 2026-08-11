<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $pembayaran->nomor_faktur }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
        }
        .struk {
            width: 300px;
            margin: 0 auto;
            padding: 10px 12px 16px;
        }
        /* Header */
        .store-name {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .store-sub {
            text-align: center;
            font-size: 10px;
            margin-bottom: 2px;
        }
        /* Garis */
        .line-solid  { border-top: 1px solid #000; margin: 6px 0; }
        .line-dash   { border-top: 1px dashed #000; margin: 6px 0; }
        .line-double { border-top: 3px double #000; margin: 6px 0; }
        /* Row */
        .row-flex {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin: 2px 0;
            font-size: 11px;
        }
        .row-flex .label { flex: 1; }
        .row-flex .val   { text-align: right; white-space: nowrap; margin-left: 4px; }
        .row-indent { padding-left: 10px; font-size: 10px; color: #333; }
        /* Total */
        .total-besar {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            margin: 4px 0;
        }
        /* Status stamp */
        .stamp {
            text-align: center;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 6px;
            border: 3px solid #000;
            padding: 4px 10px;
            margin: 10px auto;
            display: inline-block;
            opacity: .15;
            transform: rotate(-15deg);
            position: relative;
            left: 50%;
            transform: translateX(-50%) rotate(-15deg);
        }
        .stamp-wrap {
            text-align: center;
            overflow: hidden;
            height: 40px;
            margin: 8px 0;
        }
        /* Footer */
        .footer-text {
            text-align: center;
            font-size: 10px;
            margin-top: 4px;
        }
        .center { text-align: center; }
        .bold   { font-weight: bold; }
        .small  { font-size: 10px; }
        /* Print */
        @media print {
            body { margin: 0; }
            .struk { margin: 0; padding: 8px; }
            @page { margin: 4mm; size: 80mm auto; }
        }
    </style>
</head>
<body>
<div class="struk">

    {{-- HEADER --}}
    <div class="store-name">Sofia Laundry</div>
    <div class="store-sub">Jl. Contoh No. 123, Kota Anda</div>
    <div class="store-sub">Telp: +62 812-3456-7890</div>

    <div class="line-solid"></div>

    <div class="row-flex">
        <span class="label">No. Faktur</span>
        <span class="val bold">{{ $pembayaran->nomor_faktur }}</span>
    </div>
    <div class="row-flex">
        <span class="label">Tanggal</span>
        <span class="val">{{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</span>
    </div>
    <div class="row-flex">
        <span class="label">Kasir</span>
        <span class="val">{{ $pembayaran->transaksi->user->nama_user ?? '-' }}</span>
    </div>

    <div class="line-dash"></div>

    {{-- PELANGGAN --}}
    <div class="row-flex">
        <span class="label">Pelanggan</span>
        <span class="val bold">{{ $pembayaran->transaksi->pelanggan->nama_pelanggan ?? '-' }}</span>
    </div>
    <div class="row-flex">
        <span class="label">No. HP</span>
        <span class="val">{{ $pembayaran->transaksi->pelanggan->no_hp ?? '-' }}</span>
    </div>
    <div class="row-flex">
        <span class="label">No. Transaksi</span>
        <span class="val">#{{ str_pad($pembayaran->transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="row-flex">
        <span class="label">Tgl Masuk</span>
        <span class="val">{{ $pembayaran->transaksi->tanggal_masuk->format('d/m/Y') }}</span>
    </div>

    <div class="line-dash"></div>

    {{-- DETAIL LAYANAN --}}
    <div class="center bold small" style="margin-bottom:4px;">---- RINCIAN LAYANAN ----</div>

    @foreach ($pembayaran->transaksi->detailTransaksi as $d)
    <div class="row-flex bold">
        <span class="label">{{ $d->layanan->nama_layanan ?? '-' }}</span>
        <span class="val">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
    </div>
    <div class="row-indent">
        {{ number_format($d->berat, 2, ',', '.') }} kg
        x Rp {{ number_format($d->layanan->harga_per_kg ?? 0, 0, ',', '.') }}/kg
    </div>
    @endforeach

    @if ($pembayaran->transaksi->pewangi)
    <div class="row-flex">
        <span class="label">Pewangi ({{ $pembayaran->transaksi->pewangi->nama_barang }})</span>
        <span class="val">Termasuk</span>
    </div>
    @endif

    @php
        $tipeLabel  = ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'];
        $biayaAntar = $pembayaran->transaksi->biaya_antar;
        $tipeAntar  = $pembayaran->transaksi->tipe_antar;
        $tagihan    = $pembayaran->transaksi->total_tagihan;
        $kembalian  = $pembayaran->jumlah_bayar - $tagihan;
    @endphp

    @if ($biayaAntar > 0)
    <div class="row-flex">
        <span class="label">Antar/Jemput ({{ $tipeLabel[$tipeAntar] ?? '-' }})</span>
        <span class="val">Rp {{ number_format($biayaAntar, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="line-dash"></div>

    {{-- TOTAL --}}
    <div class="row-flex">
        <span class="label">Subtotal Layanan</span>
        <span class="val">Rp {{ number_format($pembayaran->transaksi->total_harga, 0, ',', '.') }}</span>
    </div>
    @if ($biayaAntar > 0)
    <div class="row-flex">
        <span class="label">Biaya Antar/Jemput</span>
        <span class="val">Rp {{ number_format($biayaAntar, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="line-solid"></div>

    <div class="total-besar">
        <span>TOTAL</span>
        <span>Rp {{ number_format($tagihan, 0, ',', '.') }}</span>
    </div>
    <div class="row-flex">
        <span class="label">Dibayar</span>
        <span class="val">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
    </div>
    @if ($kembalian > 0)
    <div class="row-flex bold">
        <span class="label">Kembali</span>
        <span class="val">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
    </div>
    @elseif ($tagihan > $pembayaran->jumlah_bayar)
    <div class="row-flex bold">
        <span class="label">Sisa Bayar</span>
        <span class="val">Rp {{ number_format($tagihan - $pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="line-dash"></div>

    {{-- METODE BAYAR --}}
    @php $labels = \App\Models\Pembayaran::$metodeLabels; @endphp
    <div class="row-flex">
        <span class="label">Metode Bayar</span>
        <span class="val">{{ $labels[$pembayaran->metode_bayar] ?? $pembayaran->metode_bayar }}</span>
    </div>
    @if ($pembayaran->nomor_referensi)
    <div class="row-flex">
        <span class="label">No. Referensi</span>
        <span class="val">{{ $pembayaran->nomor_referensi }}</span>
    </div>
    @endif

    <div class="line-double"></div>

    {{-- STATUS STAMP --}}
    @if ($pembayaran->status_bayar === 'lunas')
    <div class="stamp-wrap">
        <span class="stamp">LUNAS</span>
    </div>
    @else
    <div class="center bold small" style="margin: 6px 0;">
        ** BELUM LUNAS **
    </div>
    @endif

    @if ($pembayaran->catatan)
    <div class="small" style="margin:4px 0;">Ket: {{ $pembayaran->catatan }}</div>
    @endif

    <div class="line-dash"></div>

    {{-- FOOTER --}}
    <div class="footer-text">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    <div class="footer-text" style="margin-top:8px;">
        *** Terima kasih telah menggunakan ***
    </div>
    <div class="footer-text bold">Sofia Laundry</div>
    <div class="footer-text">Simpan struk ini sebagai bukti pembayaran</div>

</div>
</body>
</html>
