<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $pembayaran->nomor_faktur }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #f3f4f6;
        }
        /* Wrapper halaman — beri shadow agar terlihat seperti kertas */
        .page-wrap {
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 12px;
        }
        /* Toolbar cetak — hanya tampil di browser, hilang saat print */
        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
            align-items: center;
        }
        .btn-print {
            background: #000;
            color: #fff;
            border: none;
            padding: 8px 22px;
            font-family: Arial, sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 4px;
            letter-spacing: .5px;
        }
        .btn-print:hover { background: #333; }
        .btn-back {
            background: transparent;
            color: #374151;
            border: 1px solid #9ca3af;
            padding: 8px 18px;
            font-family: Arial, sans-serif;
            font-size: 13px;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
        }
        /* Struk */
        .struk {
            width: 300px;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
            padding: 10px 14px 18px;
        }
        /* Gigi atas struk */
        .struk-top {
            height: 10px;
            background: repeating-linear-gradient(
                -45deg,
                #fff 0, #fff 5px,
                #f3f4f6 5px, #f3f4f6 10px
            );
            margin-bottom: 12px;
            margin-left: -14px;
            margin-right: -14px;
        }
        /* Gigi bawah struk */
        .struk-bottom {
            height: 10px;
            background: repeating-linear-gradient(
                45deg,
                #fff 0, #fff 5px,
                #f3f4f6 5px, #f3f4f6 10px
            );
            margin-top: 12px;
            margin-left: -14px;
            margin-right: -14px;
        }
        .s-name { text-align:center; font-size:16px; font-weight:900; letter-spacing:2px; text-transform:uppercase; }
        .s-sub  { text-align:center; font-size:10px; color:#555; margin-top:1px; }
        .s-solid { border:none; border-top:1px solid #000; margin:7px 0; }
        .s-dash  { border:none; border-top:1px dashed #555; margin:7px 0; }
        .s-double{ border:none; border-top:3px double #000; margin:7px 0; }
        .s-row {
            display:flex; justify-content:space-between;
            align-items:baseline; margin:2.5px 0; font-size:11.5px;
        }
        .s-lbl { flex:1; }
        .s-val { text-align:right; white-space:nowrap; margin-left:6px; }
        .s-indent { padding-left:12px; font-size:10px; color:#555; margin:1.5px 0; }
        .s-section { text-align:center; font-size:10.5px; font-weight:700; margin:5px 0; }
        .s-total { display:flex; justify-content:space-between; font-size:15px; font-weight:900; margin:5px 0; }
        .s-stamp-wrap { text-align:center; overflow:hidden; height:42px; margin:10px 0; }
        .s-stamp {
            display:inline-block; border:3px solid #000;
            padding:3px 12px; font-size:21px; font-weight:900;
            letter-spacing:6px; opacity:.15;
            transform:rotate(-14deg) translateY(-4px);
        }
        .s-foot { text-align:center; font-size:10px; color:#555; margin-top:3px; }
        .bold  { font-weight:700; }
        .center{ text-align:center; }

        /* === PRINT STYLES === */
        @media print {
            body { background: #fff; }
            .page-wrap { background:#fff; padding:0; min-height:auto; }
            .toolbar { display: none !important; }
            .struk {
                box-shadow: none;
                width: 100%;
                max-width: 80mm;
                padding: 4px 8px 8px;
            }
            .struk-top, .struk-bottom { display: none; }
            @page {
                size: 80mm auto;
                margin: 4mm 2mm;
            }
        }
    </style>
</head>
<body>
<div class="page-wrap">

    {{-- Toolbar (hanya tampil di browser) --}}
    <div class="toolbar">
        <button class="btn-print" onclick="window.print()">
            &#128438; Cetak / Simpan PDF
        </button>
        <a class="btn-back" href="javascript:history.back()">&#8592; Kembali</a>
    </div>

    {{-- Struk --}}
    <div class="struk">
        <div class="struk-top"></div>

        {{-- Header --}}
        <div class="s-name">Sofia Laundry</div>
        <div class="s-sub">Jl. Contoh No. 123, Kota Anda</div>
        <div class="s-sub">Telp: +62 812-3456-7890</div>

        <hr class="s-solid">

        <div class="s-row">
            <span class="s-lbl">No. Faktur</span>
            <span class="s-val bold">{{ $pembayaran->nomor_faktur }}</span>
        </div>
        <div class="s-row">
            <span class="s-lbl">Tanggal</span>
            <span class="s-val">{{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</span>
        </div>
        <div class="s-row">
            <span class="s-lbl">No. Transaksi</span>
            <span class="s-val">#{{ str_pad($pembayaran->transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="s-row">
            <span class="s-lbl">Tgl Masuk</span>
            <span class="s-val">{{ $pembayaran->transaksi->tanggal_masuk->format('d/m/Y') }}</span>
        </div>

        <hr class="s-dash">

        {{-- Pelanggan --}}
        <div class="s-row">
            <span class="s-lbl">Nama</span>
            <span class="s-val bold">{{ $pelanggan->nama_pelanggan }}</span>
        </div>
        <div class="s-row">
            <span class="s-lbl">No. HP</span>
            <span class="s-val">{{ $pelanggan->no_hp ?? '-' }}</span>
        </div>

        <hr class="s-dash">

        {{-- Rincian Layanan --}}
        <div class="s-section">---- RINCIAN LAYANAN ----</div>

        @foreach ($pembayaran->transaksi->detailTransaksi as $d)
        <div class="s-row bold">
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

        @php
            $biayaAJ = $pembayaran->transaksi->booking?->biaya_antar_jemput ?? 0;
            $tipeMap = ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'];
        @endphp
        @if ($biayaAJ > 0)
        <div class="s-row">
            <span class="s-lbl">
                Antar/Jemput
                ({{ $tipeMap[$pembayaran->transaksi->booking->tipe_antar_jemput] ?? '-' }})
            </span>
            <span class="s-val">Rp {{ number_format($biayaAJ, 0, ',', '.') }}</span>
        </div>
        @endif

        <hr class="s-dash">

        {{-- Total --}}
        @php
            $tagihan   = $pembayaran->transaksi->total_harga + $biayaAJ;
            $kembalian = $pembayaran->jumlah_bayar - $tagihan;
            $sisaBayar = $tagihan - $pembayaran->jumlah_bayar;
        @endphp

        <div class="s-row">
            <span class="s-lbl">Subtotal Layanan</span>
            <span class="s-val">Rp {{ number_format($pembayaran->transaksi->total_harga, 0, ',', '.') }}</span>
        </div>
        @if ($biayaAJ > 0)
        <div class="s-row">
            <span class="s-lbl">Biaya Antar/Jemput</span>
            <span class="s-val">Rp {{ number_format($biayaAJ, 0, ',', '.') }}</span>
        </div>
        @endif

        <hr class="s-solid">

        <div class="s-total">
            <span>TOTAL</span>
            <span>Rp {{ number_format($tagihan, 0, ',', '.') }}</span>
        </div>
        <div class="s-row">
            <span class="s-lbl">Dibayar</span>
            <span class="s-val">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
        </div>
        @if ($kembalian > 0)
        <div class="s-row bold">
            <span class="s-lbl">Kembali</span>
            <span class="s-val">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
        </div>
        @elseif ($sisaBayar > 0)
        <div class="s-row bold">
            <span class="s-lbl">Sisa Bayar</span>
            <span class="s-val">Rp {{ number_format($sisaBayar, 0, ',', '.') }}</span>
        </div>
        @endif

        <hr class="s-dash">

        {{-- Metode Bayar --}}
        @php $labels = \App\Models\Pembayaran::$metodeLabels; @endphp
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

        <hr class="s-double">

        {{-- Status Stamp --}}
        @if ($pembayaran->status_bayar === 'lunas')
        <div class="s-stamp-wrap">
            <span class="s-stamp">LUNAS</span>
        </div>
        @else
        <div class="center bold" style="font-size:12px;margin:8px 0;">
            *** BELUM LUNAS ***
        </div>
        @endif

        <hr class="s-dash">

        {{-- Footer --}}
        <div class="s-foot">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
        <div class="s-foot" style="margin-top:8px;font-weight:700;">*** Terima kasih ***</div>
        <div class="s-foot bold" style="font-size:12px;">Sofia Laundry</div>
        <div class="s-foot">Simpan struk sebagai bukti pembayaran</div>

        <div class="struk-bottom"></div>
    </div>

</div>
</body>
</html>
