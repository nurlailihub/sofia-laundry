<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Faktur Transaksi #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a202c; background: #fff; }
.page { width: 190mm; padding: 10mm 12mm; margin: 0 auto; }

.no-print { padding: 6px 0 10px; text-align: right; }
.btn-cetak { background:#005F73; color:#fff; border:none; padding:6px 18px; border-radius:5px; cursor:pointer; font-size:12px; margin-left:4px; }
.btn-tutup  { background:#64748b; color:#fff; border:none; padding:6px 18px; border-radius:5px; cursor:pointer; font-size:12px; }

.hdr { width:100%; border-collapse:collapse; background-color:#005F73; }
.hdr td { padding:13px 16px; vertical-align:top; }
.brand { font-size:18px; font-weight:800; color:#fff; }
.brand-sub { font-size:9px; color:rgba(255,255,255,0.75); margin-top:3px; }
.inv-no-label { font-size:8px; color:rgba(255,255,255,0.65); text-transform:uppercase; letter-spacing:.5px; text-align:right; }
.inv-no { font-size:17px; font-weight:800; color:#fff; text-align:right; }
.inv-date { font-size:9px; color:rgba(255,255,255,0.7); text-align:right; margin-top:3px; }
.status-row { width:100%; border-collapse:collapse; background-color:#004658; }
.status-row td { padding:5px 16px; font-size:8.5px; color:rgba(255,255,255,0.85); text-transform:uppercase; letter-spacing:.8px; }

.hr { border:none; border-top:1px solid #e2e8f0; margin:10px 0; }
.sec-label { font-size:7.5px; text-transform:uppercase; letter-spacing:.6px; color:#94a3b8; font-weight:700; margin-bottom:5px; }

.info-tbl { width:100%; border-collapse:collapse; margin-bottom:10px; }
.info-box { background:#f8fafc; border:1px solid #e2e8f0; padding:8px 10px; vertical-align:top; width:49%; }
.info-gap  { width:2%; }
.il { width:100%; border-collapse:collapse; margin-bottom:2px; }
.il .k { color:#64748b; font-size:9.5px; width:72px; vertical-align:top; }
.il .v { font-weight:700; font-size:9.5px; color:#1a202c; }

.antar-box { background:#f0f9ff; border:1px solid #bae6fd; padding:8px 10px; margin-bottom:10px; }
.antar-tipe { font-weight:800; color:#0369a1; font-size:10.5px; margin-bottom:3px; }
.antar-addr { color:#475569; font-size:9.5px; margin-top:2px; }
.antar-biaya { color:#1a202c; font-size:10px; margin-top:5px; }

.items { width:100%; border-collapse:collapse; margin-bottom:8px; }
.items th { background:#f1f5f9; color:#475569; font-size:8px; text-transform:uppercase; letter-spacing:.4px; padding:5px 8px; border:1px solid #cbd5e1; }
.items td { padding:5px 8px; border:1px solid #e2e8f0; font-size:11px; }
.items tr:nth-child(even) td { background:#f8fafc; }
.text-right { text-align:right; }
.text-center { text-align:center; }

.totals-wrap { width:100%; border-collapse:collapse; }
.totals-left  { width:52%; vertical-align:top; }
.totals-right { width:48%; vertical-align:top; padding-left:10px; }
.tl-row { width:100%; border-collapse:collapse; margin-bottom:3px; }
.tl-row .lbl { color:#64748b; font-size:10px; }
.tl-row .val { text-align:right; font-size:10px; }
.grand-box { background:#f0fdf4; border:2px solid #6ee7b7; padding:7px 10px; margin-top:6px; }
.grand-row { width:100%; border-collapse:collapse; }
.grand-row .gl { font-size:13px; font-weight:800; color:#059669; }
.grand-row .gr { font-size:13px; font-weight:800; color:#059669; text-align:right; }

.pay-box { background:#f8fafc; border:1px solid #e2e8f0; padding:8px 10px; margin-top:10px; }
.pay-row { width:100%; border-collapse:collapse; }
.pay-row td { vertical-align:middle; }
.badge-lunas { background:#d1fae5; color:#065f46; padding:2px 10px; font-size:9px; font-weight:800; }
.badge-belum { background:#fef3c7; color:#92400e; padding:2px 10px; font-size:9px; font-weight:800; }
.pay-meta { color:#64748b; font-size:9px; margin-top:3px; }
.pay-amt-lbl { color:#64748b; font-size:8.5px; text-align:right; }
.pay-amt { font-weight:800; color:#059669; font-size:13px; text-align:right; }
.pay-sisa { color:#dc2626; font-size:9px; text-align:right; margin-top:2px; }

.footer { text-align:center; margin-top:14px; padding-top:9px; border-top:1px solid #e2e8f0; }
.footer-thank { font-size:10px; font-weight:600; color:#475569; margin-bottom:2px; }
.footer-date  { font-size:8.5px; color:#94a3b8; }

@media print {
    .no-print { display:none !important; }
    body { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
}
</style>
</head>
<body>
<div class="page">

    <div class="no-print">
        <button class="btn-cetak" onclick="window.print()">Cetak</button>
        <button class="btn-tutup"  onclick="window.close()">Tutup</button>
    </div>

    <table class="hdr" cellpadding="0" cellspacing="0">
        <tr>
            <td width="55%">
                <div class="brand">Sofia Laundry</div>
                <div class="brand-sub">Jl. Contoh No. 1, Kota &bull; Telp: 0812-3456-7890</div>
                <div class="brand-sub" style="margin-top:2px;">Faktur Transaksi Laundry</div>
            </td>
            <td width="45%">
                <div class="inv-no-label">No. Transaksi</div>
                <div class="inv-no">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</div>
                @if($transaksi->booking)
                <div class="inv-date">Booking: {{ $transaksi->booking->kode_reservasi }}</div>
                @endif
                <div class="inv-date">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>
    <table class="status-row" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                @php
                    $statusLabel = ['proses'=>'Sedang Diproses','selesai'=>'Selesai','diambil'=>'Sudah Diambil'];
                    $detailLabel = \App\Models\Transaksi::$statusDetailLabels;
                @endphp
                STATUS: {{ strtoupper($statusLabel[$transaksi->status] ?? $transaksi->status) }}
                @if($transaksi->status_detail)
                &nbsp;&bull;&nbsp; {{ strtoupper($detailLabel[$transaksi->status_detail] ?? $transaksi->status_detail) }}
                @endif
            </td>
        </tr>
    </table>

    <hr class="hr" style="margin-top:10px;">

    <table class="info-tbl" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-box">
                <div class="sec-label">Data Pelanggan</div>
                <table class="il" cellpadding="0" cellspacing="0"><tr><td class="k">Nama</td><td class="v">{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</td></tr></table>
                <table class="il" cellpadding="0" cellspacing="0"><tr><td class="k">No. HP</td><td class="v">{{ $transaksi->pelanggan->no_hp ?? '-' }}</td></tr></table>
                <table class="il" cellpadding="0" cellspacing="0"><tr><td class="k">Alamat</td><td class="v">{{ $transaksi->pelanggan->alamat ?? '-' }}</td></tr></table>
            </td>
            <td class="info-gap"></td>
            <td class="info-box">
                <div class="sec-label">Info Transaksi</div>
                <table class="il" cellpadding="0" cellspacing="0"><tr><td class="k">Tgl Masuk</td><td class="v">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y H:i') }}</td></tr></table>
                <table class="il" cellpadding="0" cellspacing="0"><tr><td class="k">Est. Selesai</td><td class="v">{{ $transaksi->tanggal_selesai ? \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') : '-' }}</td></tr></table>
                <table class="il" cellpadding="0" cellspacing="0"><tr><td class="k">Petugas</td><td class="v">{{ $transaksi->user->nama_user ?? '-' }}</td></tr></table>
                @if($transaksi->pewangi)
                <table class="il" cellpadding="0" cellspacing="0"><tr><td class="k">Pewangi</td><td class="v">{{ $transaksi->pewangi->nama_barang }}</td></tr></table>
                @endif
            </td>
        </tr>
    </table>

    @php
        $tipeAntar = $transaksi->tipe_antar ?? 'none';
        $tipeLabel = ['none'=>'Antar Sendiri','pickup'=>'Dijemput Admin','delivery'=>'Diantar Admin','both'=>'Jemput & Antar'];
    @endphp
    @if($tipeAntar !== 'none')
    <div class="antar-box">
        <div class="sec-label">Layanan Antar / Jemput</div>
        <div class="antar-tipe">{{ $tipeLabel[$tipeAntar] ?? $tipeAntar }}</div>
        @if($transaksi->booking)
            @if($transaksi->booking->alamat_jemput)
            <div class="antar-addr">&#9679; Alamat Jemput : {{ $transaksi->booking->alamat_jemput }}</div>
            @endif
            @if($transaksi->booking->alamat_antar)
            <div class="antar-addr">&#9679; Alamat Antar&nbsp; : {{ $transaksi->booking->alamat_antar }}</div>
            @endif
        @endif
        @if($transaksi->biaya_antar > 0)
        <div class="antar-biaya">Biaya: <strong>Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</strong></div>
        @endif
    </div>
    @endif

    <div class="sec-label" style="margin-bottom:5px;">Detail Layanan</div>
    <table class="items" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th>Layanan</th>
                <th style="width:80px;text-align:center;">Berat (kg)</th>
                <th style="width:110px;text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksi as $i => $d)
            @php $isSatuan = ($d->layanan?->tipe_harga === 'satuan'); @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $d->layanan->nama_layanan ?? '-' }}</td>
                <td class="text-center">
                    {{ $isSatuan ? number_format($d->berat, 0) . ' pcs' : number_format($d->berat, 2) . ' kg' }}
                </td>
                <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-wrap" cellpadding="0" cellspacing="0">
        <tr>
            <td class="totals-left">
                @if($transaksi->catatan_status)
                <div class="sec-label" style="margin-bottom:4px;">Catatan</div>
                <div style="font-size:9.5px;color:#475569;background:#f8fafc;border:1px solid #e2e8f0;padding:6px 8px;">
                    {{ $transaksi->catatan_status }}
                </div>
                @endif
            </td>
            <td class="totals-right">
                <table class="tl-row" cellpadding="0" cellspacing="0"><tr><td class="lbl">Total Berat</td><td class="val"><strong>{{ number_format($transaksi->total_berat, 2) }} kg</strong></td></tr></table>
                <table class="tl-row" cellpadding="0" cellspacing="0"><tr><td class="lbl">Subtotal Layanan</td><td class="val">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td></tr></table>
                @if($transaksi->biaya_antar > 0)
                <table class="tl-row" cellpadding="0" cellspacing="0"><tr><td class="lbl">Biaya Antar/Jemput</td><td class="val">Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</td></tr></table>
                @endif
                <div class="grand-box">
                    <table class="grand-row" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="gl">TOTAL TAGIHAN</td>
                            <td class="gr">Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    @if($transaksi->pembayaran)
    <div class="pay-box">
        <div class="sec-label" style="margin-bottom:5px;">Informasi Pembayaran</div>
        <table class="pay-row" cellpadding="0" cellspacing="0">
            <tr>
                <td width="55%">
                    @if($transaksi->pembayaran->status_bayar === 'lunas')
                    <span class="badge-lunas">LUNAS</span>
                    @else
                    <span class="badge-belum">BELUM LUNAS</span>
                    @endif
                    <div class="pay-meta">
                        Metode: {{ strtoupper($transaksi->pembayaran->metode_bayar ?? '-') }}
                        @if($transaksi->pembayaran->tanggal_bayar)
                        &nbsp;&bull;&nbsp; {{ \Carbon\Carbon::parse($transaksi->pembayaran->tanggal_bayar)->format('d/m/Y H:i') }}
                        @endif
                    </div>
                    @if($transaksi->pembayaran->catatan)
                    <div class="pay-meta" style="margin-top:3px;">Catatan: {{ $transaksi->pembayaran->catatan }}</div>
                    @endif
                </td>
                <td width="45%">
                    <div class="pay-amt-lbl">Jumlah Dibayar</div>
                    <div class="pay-amt">Rp {{ number_format($transaksi->pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
                    @php $sisa = $transaksi->total_tagihan - $transaksi->pembayaran->jumlah_bayar; @endphp
                    @if($sisa > 0)
                    <div class="pay-sisa">Sisa: Rp {{ number_format($sisa, 0, ',', '.') }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @else
    <div style="margin-top:10px;padding:8px 10px;background:#fef3c7;border:1px solid #fde68a;font-size:9.5px;color:#92400e;">
        Belum ada catatan pembayaran untuk transaksi ini.
    </div>
    @endif

    <div class="footer">
        <div class="footer-thank">Terima kasih telah mempercayakan cucian Anda kepada Sofia Laundry</div>
        <div class="footer-date">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

</div>
</body>
</html>
