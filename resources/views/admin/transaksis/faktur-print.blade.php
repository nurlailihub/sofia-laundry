<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Faktur #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'Arial', sans-serif; font-size: 12px; color: #1a1a2e; background: #fff; }
.page { width: 148mm; min-height: 210mm; padding: 10mm; margin: 0 auto; }
.header { background: linear-gradient(135deg, #005F73, #2BB1B1); color: #fff; padding: 12px 16px; border-radius: 8px; margin-bottom: 14px; }
.header h1 { font-size: 18px; font-weight: 800; margin-bottom: 2px; }
.header .sub { font-size: 9px; opacity: .8; }
.inv-num { text-align: right; }
.inv-num .label { font-size: 8px; opacity: .7; text-transform: uppercase; }
.inv-num .num { font-size: 16px; font-weight: 800; }
.section-title { font-size: 8px; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; margin-bottom: 4px; font-weight: 700; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px; }
.info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 10px; }
.info-row { display: flex; gap: 6px; margin-bottom: 2px; }
.info-row .key { color: #6b7280; width: 70px; flex-shrink: 0; }
.info-row .val { font-weight: 600; }
.antar-box { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px; padding: 8px 10px; margin-bottom: 12px; }
table.items { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
table.items th { background: #f1f5f9; font-size: 9px; text-transform: uppercase; padding: 5px 8px; border: 1px solid #e2e8f0; }
table.items td { padding: 5px 8px; border: 1px solid #e2e8f0; font-size: 11px; }
.totals { margin-left: auto; width: 200px; }
.total-row { display: flex; justify-content: space-between; padding: 2px 0; }
.total-row.grand { background: #f0fdf4; border: 2px solid #6ee7b7; border-radius: 6px; padding: 6px 10px; margin-top: 4px; font-size: 13px; font-weight: 800; color: #059669; }
.payment-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 10px; margin-top: 12px; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 9px; font-weight: 700; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-warning { background: #fef3c7; color: #92400e; }
.footer { text-align: center; margin-top: 14px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #9ca3af; }
@media print {
    .no-print { display: none !important; }
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
</head>
<body>
<div class="page">

    <div class="no-print" style="padding:8px 0 12px; text-align:right;">
        <button onclick="window.print()" style="background:#005F73;color:#fff;border:none;padding:6px 16px;border-radius:6px;cursor:pointer;font-size:12px;">
            🖨️ Cetak
        </button>
        <button onclick="window.close()" style="background:#6b7280;color:#fff;border:none;padding:6px 16px;border-radius:6px;cursor:pointer;font-size:12px;margin-left:6px;">
            ✕ Tutup
        </button>
    </div>

    <div class="header" style="display:flex;justify-content:space-between;align-items:flex-start;">
        <div>
            <h1>Sofia Laundry</h1>
            <div class="sub">Jl. Contoh No. 1, Kota</div>
            <div class="sub">Faktur Transaksi</div>
        </div>
        <div class="inv-num">
            <div class="label">No. Transaksi</div>
            <div class="num">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</div>
            @if($transaksi->booking)
            <div style="font-size:9px;opacity:.8;">{{ $transaksi->booking->kode_reservasi }}</div>
            @endif
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <div class="section-title">Data Pelanggan</div>
            <div class="info-row"><span class="key">Nama</span><span class="val">{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</span></div>
            <div class="info-row"><span class="key">No. HP</span><span class="val">{{ $transaksi->pelanggan->no_hp ?? '-' }}</span></div>
            <div class="info-row"><span class="key">Alamat</span><span class="val">{{ $transaksi->pelanggan->alamat ?? '-' }}</span></div>
        </div>
        <div class="info-box">
            <div class="section-title">Info Transaksi</div>
            <div class="info-row"><span class="key">Tgl Masuk</span><span class="val">{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y H:i') }}</span></div>
            <div class="info-row"><span class="key">Est. Selesai</span><span class="val">{{ $transaksi->tanggal_selesai ? \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') : '-' }}</span></div>
            <div class="info-row"><span class="key">Petugas</span><span class="val">{{ $transaksi->user->nama_user ?? '-' }}</span></div>
            @if($transaksi->pewangi)
            <div class="info-row"><span class="key">Pewangi</span><span class="val">{{ $transaksi->pewangi->nama_barang }}</span></div>
            @endif
        </div>
    </div>

    @php
        $tipeAntar = $transaksi->tipe_antar ?? 'none';
        $tipeAntarLabel = ['none'=>'Antar Sendiri','pickup'=>'Dijemput Admin','delivery'=>'Diantar Admin','both'=>'Jemput & Antar'];
    @endphp
    @if($tipeAntar !== 'none')
    <div class="antar-box" style="margin-bottom:12px;">
        <div class="section-title">Layanan Antar/Jemput</div>
        <div style="font-weight:700;color:#0891b2;margin-bottom:3px;">{{ $tipeAntarLabel[$tipeAntar] ?? '' }}</div>
        @if($transaksi->booking)
            @if($transaksi->booking->alamat_jemput)
            <div style="color:#6b7280;font-size:10px;">Jemput: {{ $transaksi->booking->alamat_jemput }}</div>
            @endif
            @if($transaksi->booking->alamat_antar)
            <div style="color:#6b7280;font-size:10px;">Antar: {{ $transaksi->booking->alamat_antar }}</div>
            @endif
        @endif
        @if($transaksi->biaya_antar > 0)
        <div style="margin-top:4px;">Biaya: <strong>Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</strong></div>
        @endif
    </div>
    @endif

    <table class="items">
        <thead>
            <tr>
                <th>Layanan</th>
                <th style="text-align:center;width:70px;">Berat (kg)</th>
                <th style="text-align:right;width:100px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksi as $d)
            <tr>
                <td>{{ $d->layanan->nama_layanan ?? '-' }}</td>
                <td style="text-align:center;">{{ number_format($d->berat, 2) }}</td>
                <td style="text-align:right;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row"><span style="color:#6b7280;">Total Berat</span><span><strong>{{ number_format($transaksi->total_berat, 2) }} kg</strong></span></div>
        <div class="total-row"><span style="color:#6b7280;">Subtotal Layanan</span><span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></div>
        @if($transaksi->biaya_antar > 0)
        <div class="total-row"><span style="color:#6b7280;">Biaya Antar/Jemput</span><span>Rp {{ number_format($transaksi->biaya_antar, 0, ',', '.') }}</span></div>
        @endif
        <div class="total-row grand">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaksi->total_tagihan, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($transaksi->pembayaran)
    <div class="payment-box">
        <div class="section-title">Pembayaran</div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
            <div>
                <span class="badge {{ $transaksi->pembayaran->status_bayar === 'lunas' ? 'badge-success' : 'badge-warning' }}">
                    {{ $transaksi->pembayaran->status_bayar === 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
                <span style="margin-left:6px;color:#6b7280;font-size:10px;">{{ strtoupper($transaksi->pembayaran->metode_bayar ?? '-') }}</span>
            </div>
            <div style="text-align:right;">
                <div style="color:#6b7280;font-size:9px;">Dibayar</div>
                <div style="font-weight:700;color:#059669;">Rp {{ number_format($transaksi->pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Terima kasih telah mempercayakan cucian Anda kepada <strong>Sofia Laundry</strong></p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</div>
</body>
</html>
