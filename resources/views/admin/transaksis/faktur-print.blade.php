<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Faktur #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }} — Sofia Laundry</title>
<style>
/* ── Reset ── */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
html { font-size: 13px; }
body { font-family: Arial, sans-serif; color: #1a1a2e; background: #e5e7eb; }

/* ── Preview wrapper (hanya tampil di layar) ── */
.preview-bar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #1f2937;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.preview-bar .title { font-size: 14px; font-weight: 600; }
.preview-bar .subtitle { font-size: 11px; color: #9ca3af; margin-top: 2px; }
.btn-print {
    background: #10b981;
    color: #fff;
    border: none;
    padding: 9px 22px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 7px;
    text-decoration: none;
}
.btn-print:hover { background: #059669; }
.btn-close-preview {
    background: #6b7280;
    color: #fff;
    border: none;
    padding: 9px 18px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-close-preview:hover { background: #4b5563; }

/* ── Halaman faktur ── */
.page-wrap {
    display: flex;
    justify-content: center;
    padding: 24px 16px 40px;
}
.page {
    width: 210mm;
    min-height: 148mm;
    padding: 14mm 16mm;
    background: #fff;
    box-shadow: 0 4px 32px rgba(0,0,0,.18);
    border-radius: 2px;
}

/* ── Header ── */
.header {
    background: linear-gradient(135deg, #005F73 0%, #2BB1B1 100%);
    color: #fff;
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.header h1 { font-size: 20px; font-weight: 800; margin-bottom: 2px; }
.header .sub { font-size: 9.5px; opacity: .8; margin-top: 1px; }
.inv-num { text-align: right; }
.inv-num .label { font-size: 8px; opacity: .7; text-transform: uppercase; letter-spacing: .5px; }
.inv-num .num { font-size: 18px; font-weight: 800; line-height: 1.2; }
.inv-num .kode { font-size: 10px; opacity: .8; margin-top: 2px; }

/* ── Info grid (2 kolom) ── */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 14px;
}
.info-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 9px 12px;
}
.section-title {
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #6b7280;
    margin-bottom: 5px;
    font-weight: 700;
}
.info-row {
    display: flex;
    gap: 6px;
    margin-bottom: 3px;
    font-size: 11px;
}
.info-row .key { color: #6b7280; width: 80px; flex-shrink: 0; }
.info-row .val { font-weight: 600; word-break: break-word; }

/* ── Antar/Jemput box ── */
.antar-box {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 6px;
    padding: 9px 12px;
    margin-bottom: 14px;
    font-size: 11px;
}
.antar-box .type { font-weight: 700; color: #0891b2; margin-bottom: 3px; }
.antar-box .addr { color: #6b7280; font-size: 10px; margin-top: 2px; }
.antar-box .biaya { margin-top: 5px; }

/* ── Tabel detail ── */
table.items {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 12px;
    font-size: 11.5px;
}
table.items th {
    background: #f1f5f9;
    font-size: 8.5px;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 6px 9px;
    border: 1px solid #e2e8f0;
    color: #475569;
}
table.items td {
    padding: 6px 9px;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
}
table.items tr:nth-child(even) td { background: #fafafa; }

/* ── Totals ── */
.totals-wrap { display: flex; justify-content: flex-end; }
.totals { width: 230px; }
.total-row {
    display: flex;
    justify-content: space-between;
    padding: 3px 0;
    font-size: 11.5px;
}
.total-row .lbl { color: #6b7280; }
.total-row.grand {
    background: #f0fdf4;
    border: 2px solid #6ee7b7;
    border-radius: 6px;
    padding: 7px 10px;
    margin-top: 6px;
    font-size: 14px;
    font-weight: 800;
    color: #059669;
}

/* ── Pembayaran ── */
.payment-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 9px 12px;
    margin-top: 14px;
    font-size: 11px;
}
.badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 9999px;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
}
.badge-lunas { background: #d1fae5; color: #065f46; }
.badge-belum { background: #fef3c7; color: #92400e; }

/* ── Footer ── */
.footer {
    text-align: center;
    margin-top: 16px;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    font-size: 9px;
    color: #9ca3af;
}

/* ── PRINT styles ── */
@media print {
    html { font-size: 12px; }
    body { background: #fff; }
    .preview-bar { display: none !important; }
    .page-wrap { display: block; padding: 0; }
    .page {
        width: 100%;
        min-height: auto;
        padding: 10mm 12mm;
        box-shadow: none;
        border-radius: 0;
    }
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

    @page { size: A5 portrait; margin: 8mm; }
}
</style>
</head>
<body>

{{-- ── Toolbar Preview (hilang saat cetak) ── --}}
<div class="preview-bar">
    <div>
        <div class="title">Preview Faktur Transaksi</div>
        <div class="subtitle">Periksa faktur sebelum mencetak</div>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        <button class="btn-close-preview" onclick="window.history.back()">
            &#8592; Kembali
        </button>
        <button class="btn-print" onclick="window.print()">
            🖨️ &nbsp;Cetak Faktur
        </button>
    </div>
</div>

<div class="page-wrap">
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div>
            <h1>Sofia Laundry</h1>
            <div class="sub">Jl. Contoh No. 1, Kota &nbsp;|&nbsp; +62 812-3456-7890</div>
            <div class="sub">Faktur Transaksi Laundry</div>
        </div>
        <div class="inv-num">
            <div class="label">No. Transaksi</div>
            <div class="num">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</div>
            @if($transaksi->booking)
            <div class="kode">{{ $transaksi->booking->kode_reservasi }}</div>
            @endif
        </div>
    </div>

    {{-- Info grid --}}
    <div class="info-grid">
        <div class="info-box">
            <div class="section-title">Data Pelanggan</div>
            <div class="info-row">
                <span class="key">Nama</span>
                <span class="val">{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="key">No. HP</span>
                <span class="val">{{ $transaksi->pelanggan->no_hp ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="key">Alamat</span>
                <span class="val">{{ $transaksi->pelanggan->alamat ?? '-' }}</span>
            </div>
        </div>
        <div class="info-box">
            <div class="section-title">Info Transaksi</div>
            <div class="info-row">
                <span class="key">Tgl Masuk</span>
                <span class="val">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="key">Est. Selesai</span>
                <span class="val">
                    {{ $transaksi->tanggal_selesai
                        ? \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y')
                        : '-' }}
                </span>
            </div>
            <div class="info-row">
                <span class="key">Petugas</span>
                <span class="val">{{ $transaksi->user->nama_user ?? '-' }}</span>
            </div>
            @if($transaksi->pewangi)
            <div class="info-row">
                <span class="key">Pewangi</span>
                <span class="val">{{ $transaksi->pewangi->nama_barang }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Antar/Jemput info --}}
    @php
        $tipeAntar = $transaksi->tipe_antar ?? 'none';
        $tipeAntarLabel = [
            'none'     => 'Antar Sendiri',
            'pickup'   => 'Dijemput Admin',
            'delivery' => 'Diantar Admin',
            'both'     => 'Jemput & Antar',
        ];
    @endphp
    @if($tipeAntar !== 'none')
    <div class="antar-box">
        <div class="section-title">Layanan Antar / Jemput</div>
        <div class="type">🚚 {{ $tipeAntarLabel[$tipeAntar] ?? $tipeAntar }}</div>
        @if($transaksi->booking)
            @if($transaksi->booking->alamat_jemput)
            <div class="addr">📍 Jemput: {{ $transaksi->booking->alamat_jemput }}</div>
            @endif
            @if($transaksi->booking->alamat_antar)
            <div class="addr">📍 Antar: {{ $transaksi->booking->alamat_antar }}</div>
            @endif
        @endif
        @if($transaksi->biaya_antar > 0)
        <div class="biaya">Biaya: <strong>Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</strong></div>
        @endif
    </div>
    @endif

    {{-- Detail layanan --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width:50%;">Layanan</th>
                <th style="text-align:center;width:90px;">Berat (kg)</th>
                <th style="text-align:right;">Harga/kg</th>
                <th style="text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksi as $d)
            <tr>
                <td>{{ $d->layanan->nama_layanan ?? '-' }}</td>
                <td style="text-align:center;">{{ number_format($d->berat, 2) }}</td>
                <td style="text-align:right;">
                    Rp {{ number_format($d->layanan->harga_per_kg ?? 0, 0, ',', '.') }}
                </td>
                <td style="text-align:right;">
                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals-wrap">
        <div class="totals">
            <div class="total-row">
                <span class="lbl">Total Berat</span>
                <span><strong>{{ number_format($transaksi->total_berat, 2) }} kg</strong></span>
            </div>
            <div class="total-row">
                <span class="lbl">Subtotal Layanan</span>
                <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            @if($transaksi->biaya_antar > 0)
            <div class="total-row">
                <span class="lbl">Biaya Antar/Jemput</span>
                <span>Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row grand">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Pembayaran --}}
    @if($transaksi->pembayaran)
    <div class="payment-box">
        <div class="section-title">Status Pembayaran</div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;">
            <div>
                <span class="badge {{ $transaksi->pembayaran->status_bayar === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                    {{ $transaksi->pembayaran->status_bayar === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
                <span style="margin-left:8px;color:#6b7280;font-size:10px;">
                    {{ strtoupper($transaksi->pembayaran->metode_bayar ?? '-') }}
                </span>
            </div>
            <div style="text-align:right;">
                <div style="color:#6b7280;font-size:9px;">Jumlah Dibayar</div>
                <div style="font-weight:700;color:#059669;font-size:13px;">
                    Rp {{ number_format($transaksi->pembayaran->jumlah_bayar, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>Terima kasih telah mempercayakan cucian Anda kepada <strong>Sofia Laundry</strong></p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</div>
</div>

</body>
</html>
