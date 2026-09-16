<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran — {{ $pembayaran->nomor_faktur }}</title>
    <meta name="description" content="Struk pembayaran laundry Sofia Laundry untuk {{ $pembayaran->transaksi->pelanggan->nama_pelanggan ?? '' }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px;
        }

        /* ── Banner atas ── */
        .top-banner {
            width: 100%;
            max-width: 420px;
            background: linear-gradient(135deg, #005F73, #2BB1B1);
            color: #fff;
            border-radius: 12px 12px 0 0;
            padding: 16px 20px;
            text-align: center;
        }
        .brand { font-size: 22px; font-weight: 800; letter-spacing: 1px; }
        .brand-sub { font-size: 11px; opacity: .8; margin-top: 2px; }

        /* ── Kartu struk ── */
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,.10);
            overflow: hidden;
        }

        .card-body { padding: 20px 22px; }

        /* ── Status badge ── */
        .status-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 22px;
            font-size: 12px;
            font-weight: 700;
        }
        .status-lunas { background: #d1fae5; color: #065f46; }
        .status-belum { background: #fef3c7; color: #92400e; }

        /* ── Info rows ── */
        .section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #9ca3af;
            margin: 14px 0 6px;
            font-weight: 700;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 4px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .lbl { color: #6b7280; }
        .info-row .val { font-weight: 600; text-align: right; max-width: 60%; }

        /* ── Layanan table ── */
        .layanan-item {
            padding: 8px 0;
            border-bottom: 1px dashed #e5e7eb;
        }
        .layanan-item:last-child { border-bottom: none; }
        .layanan-name { font-weight: 700; font-size: 13px; }
        .layanan-sub  { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .layanan-price { font-weight: 700; font-size: 13px; color: #059669; }

        /* ── Totals ── */
        .total-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px 14px;
            margin-top: 12px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 12.5px;
        }
        .total-row .lbl { color: #6b7280; }
        .total-grand {
            display: flex;
            justify-content: space-between;
            padding: 10px 0 0;
            margin-top: 6px;
            border-top: 2px solid #e5e7eb;
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }
        .total-grand .val-grand { color: #059669; }

        /* ── Stamp ── */
        .stamp-wrap { text-align: center; padding: 14px 0 4px; }
        .stamp-lunas {
            display: inline-block;
            border: 3px solid #059669;
            color: #059669;
            padding: 4px 18px;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 6px;
            border-radius: 4px;
            transform: rotate(-8deg);
            opacity: .7;
        }
        .stamp-belum {
            display: inline-block;
            border: 3px solid #d97706;
            color: #d97706;
            padding: 4px 14px;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 3px;
            border-radius: 4px;
            transform: rotate(-8deg);
            opacity: .7;
        }

        /* ── Footer note ── */
        .footer-note {
            text-align: center;
            padding: 14px 20px 18px;
            font-size: 11px;
            color: #9ca3af;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
        }
        .footer-note strong { color: #374151; }

        /* ── Divider ── */
        .divider { border: none; border-top: 1px dashed #d1d5db; margin: 2px 0; }

        /* ── Responsive ── */
        @media (max-width: 460px) {
            body { padding: 0; background: #fff; }
            .top-banner { border-radius: 0; }
            .card { border-radius: 0; box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="top-banner">
    <div class="brand">{{ config('app.store_name', 'Sofia Laundry') }}</div>
    <div class="brand-sub">Struk Pembayaran Digital</div>
</div>

@php
    $transaksi  = $pembayaran->transaksi;
    $pelanggan  = $transaksi->pelanggan;
    $biayaAntar = $transaksi->biaya_antar;
    $tipeAntar  = $transaksi->tipe_antar;
    $tagihan    = $transaksi->total_tagihan;
    $kembalian  = $pembayaran->jumlah_bayar - $tagihan;
    $labels     = \App\Models\Pembayaran::$metodeLabels;
    $isLunas    = $pembayaran->status_bayar === 'lunas';
@endphp

<div class="card">

    {{-- Status bar --}}
    <div class="status-bar {{ $isLunas ? 'status-lunas' : 'status-belum' }}">
        <span>
            @if($isLunas)
                ✅ PEMBAYARAN LUNAS
            @else
                ⚠️ BELUM LUNAS
            @endif
        </span>
        <span>{{ $pembayaran->nomor_faktur }}</span>
    </div>

    <div class="card-body">

        {{-- Info Transaksi --}}
        <div class="section-title">📋 Info Transaksi</div>
        <div class="info-row">
            <span class="lbl">No. Transaksi</span>
            <span class="val">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Tanggal Bayar</span>
            <span class="val">{{ $pembayaran->tanggal_bayar->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Tgl Masuk</span>
            <span class="val">{{ $transaksi->tanggal_masuk->format('d/m/Y') }}</span>
        </div>
        @if($transaksi->tanggal_selesai)
        <div class="info-row">
            <span class="lbl">Tgl Selesai</span>
            <span class="val">{{ \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="lbl">Kasir</span>
            <span class="val">{{ $transaksi->user->nama_user ?? '-' }}</span>
        </div>

        {{-- Info Pelanggan --}}
        <div class="section-title">👤 Data Pelanggan</div>
        <div class="info-row">
            <span class="lbl">Nama</span>
            <span class="val">{{ $pelanggan->nama_pelanggan ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">No. HP</span>
            <span class="val">{{ $pelanggan->no_hp ?? '-' }}</span>
        </div>
        @if($pelanggan->alamat ?? false)
        <div class="info-row">
            <span class="lbl">Alamat</span>
            <span class="val">{{ $pelanggan->alamat }}</span>
        </div>
        @endif

        {{-- Antar/Jemput --}}
        @if($tipeAntar !== 'none' && $biayaAntar > 0)
        <div class="section-title">🚚 Layanan Antar/Jemput</div>
        <div class="info-row">
            <span class="lbl">Tipe</span>
            <span class="val">{{ $tipeLabel[$tipeAntar] ?? $tipeAntar }}</span>
        </div>
        @if($transaksi->booking?->alamat_jemput)
        <div class="info-row">
            <span class="lbl">Alamat Jemput</span>
            <span class="val">{{ $transaksi->booking->alamat_jemput }}</span>
        </div>
        @endif
        @if($transaksi->booking?->alamat_antar)
        <div class="info-row">
            <span class="lbl">Alamat Antar</span>
            <span class="val">{{ $transaksi->booking->alamat_antar }}</span>
        </div>
        @endif
        @endif

        {{-- Rincian Layanan --}}
        <div class="section-title">🧺 Rincian Layanan</div>
        @foreach($transaksi->detailTransaksi as $d)
        <div class="layanan-item">
            <div style="display:flex;justify-content:space-between;align-items:start;">
                <div>
                    <div class="layanan-name">{{ $d->layanan->nama_layanan ?? '-' }}</div>
                    <div class="layanan-sub">
                        {{ number_format($d->berat, 2, ',', '.') }} kg
                        × Rp {{ number_format($d->layanan->harga_per_kg ?? 0, 0, ',', '.') }}/kg
                    </div>
                </div>
                <div class="layanan-price">
                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                </div>
            </div>
        </div>
        @endforeach

        @if($transaksi->pewangi)
        <div class="info-row" style="margin-top:4px;">
            <span class="lbl">Pewangi ({{ $transaksi->pewangi->nama_barang }})</span>
            <span class="val" style="color:#6b7280;font-style:italic;">Termasuk</span>
        </div>
        @endif

        {{-- Totals --}}
        <div class="total-section">
            <div class="total-row">
                <span class="lbl">Subtotal Layanan</span>
                <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            @if($biayaAntar > 0)
            <div class="total-row">
                <span class="lbl">Biaya Antar/Jemput</span>
                <span>Rp {{ number_format($biayaAntar, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-grand">
                <span>TOTAL</span>
                <span class="val-grand">Rp {{ number_format($tagihan, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="section-title">💳 Pembayaran</div>
        <div class="info-row">
            <span class="lbl">Metode</span>
            <span class="val">{{ $labels[$pembayaran->metode_bayar] ?? $pembayaran->metode_bayar }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Jumlah Bayar</span>
            <span class="val" style="color:#059669;">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
        </div>
        @if($kembalian > 0)
        <div class="info-row">
            <span class="lbl">Kembalian</span>
            <span class="val" style="color:#2563eb;">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
        </div>
        @elseif($tagihan > $pembayaran->jumlah_bayar)
        <div class="info-row">
            <span class="lbl">Sisa Bayar</span>
            <span class="val" style="color:#ef4444;">Rp {{ number_format($tagihan - $pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($pembayaran->nomor_referensi)
        <div class="info-row">
            <span class="lbl">No. Referensi</span>
            <span class="val">{{ $pembayaran->nomor_referensi }}</span>
        </div>
        @endif
        @if($pembayaran->catatan)
        <div style="font-size:11px;color:#6b7280;padding:6px 0;border-top:1px solid #f3f4f6;margin-top:4px;">
            📝 {{ $pembayaran->catatan }}
        </div>
        @endif

        {{-- Stamp --}}
        <div class="stamp-wrap">
            @if($isLunas)
            <span class="stamp-lunas">LUNAS</span>
            @else
            <span class="stamp-belum">BELUM LUNAS</span>
            @endif
        </div>

    </div>

    <div class="footer-note">
        <strong>{{ config('app.store_name', 'Sofia Laundry') }}</strong><br>
        {{ config('app.store_address', 'Jl. Contoh No. 123, Kota Padang') }} · {{ config('app.store_phone', '0812-3456-7890') }}<br><br>
        Simpan struk ini sebagai bukti pembayaran Anda.<br>
        Terima kasih telah menggunakan layanan kami 🙏
    </div>

</div>

</body>
</html>
