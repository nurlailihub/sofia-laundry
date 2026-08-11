<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pendapatan - Sofia Laundry</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; font-size:10px; color:#333; }
.header { text-align:center; margin-bottom:14px; padding-bottom:10px; border-bottom:3px double #2c3e50; }
.header .logo { font-size:20px; font-weight:bold; color:#2c3e50; letter-spacing:2px; }
.header .tagline { font-size:10px; color:#7f8c8d; margin-top:2px; }
.header .alamat { font-size:9px; color:#95a5a6; margin-top:3px; }
.header h2 { font-size:13px; color:#2c3e50; margin-top:8px; text-transform:uppercase; letter-spacing:1px; }
.info { margin-bottom:12px; background:#f8f9fa; padding:8px 12px; border-left:4px solid #27ae60; }
.info p { margin:2px 0; font-size:9px; }
.info strong { color:#2c3e50; }
.summary { display:table; width:100%; margin-bottom:12px; border:1px solid #bdc3c7; border-radius:4px; }
.summary-cell { display:table-cell; padding:8px 12px; text-align:center; border-right:1px solid #bdc3c7; width:25%; }
.summary-cell:last-child { border-right:none; }
.summary-cell .label { font-size:8px; color:#7f8c8d; text-transform:uppercase; letter-spacing:.5px; }
.summary-cell .value { font-size:12px; font-weight:bold; color:#2c3e50; margin-top:2px; }
.summary-cell .value.green { color:#27ae60; }
.summary-cell .value.orange { color:#e67e22; }
table { width:100%; border-collapse:collapse; font-size:9px; }
table th { background:#2c3e50; color:#fff; padding:5px 6px; font-size:8px; text-transform:uppercase; letter-spacing:.3px; }
table td { border:1px solid #bdc3c7; padding:4px 6px; }
table tr:nth-child(even) { background:#f8f9fa; }
.text-right { text-align:right; }
.text-center { text-align:center; }
.badge-lunas { background:#d5f5e3; color:#1e8449; padding:1px 6px; border-radius:3px; font-weight:bold; }
.badge-belum { background:#fdebd0; color:#e67e22; padding:1px 6px; border-radius:3px; font-weight:bold; }
.grand-total td { font-weight:bold; background:#eafaf1 !important; border-top:2px solid #27ae60; }
.footer { margin-top:20px; text-align:right; font-size:8px; color:#95a5a6; border-top:1px solid #dee2e6; padding-top:5px; }
</style>
</head>
<body>

<div class="header">
    <div class="logo">SOFIA LAUNDRY</div>
    <div class="tagline">Laundry Management System</div>
    <div class="alamat">Jl. Contoh Alamat No. 123, Kota | Telp: 0812-3456-7890</div>
    <h2>Laporan Pendapatan</h2>
</div>

<div class="info">
    <p><strong>Periode:</strong>
        @if($tanggal_mulai && $tanggal_akhir)
            {{ $tanggal_mulai }} &mdash; {{ $tanggal_akhir }}
        @elseif($tanggal_mulai)
            Mulai {{ $tanggal_mulai }}
        @elseif($tanggal_akhir)
            Sampai {{ $tanggal_akhir }}
        @else
            Semua periode
        @endif
    </p>
    <p><strong>Status Pembayaran:</strong> {{ $status_filter }}</p>
    <p><strong>Tanggal Cetak:</strong> {{ $tanggal_cetak }}</p>
</div>

<div class="summary">
    <div class="summary-cell">
        <div class="label">Total Transaksi</div>
        <div class="value">{{ $statistik['total_transaksi'] }}</div>
    </div>
    <div class="summary-cell">
        <div class="label">Total Tagihan</div>
        <div class="value">Rp {{ number_format($statistik['total_tagihan'], 0, ',', '.') }}</div>
    </div>
    <div class="summary-cell">
        <div class="label">Sudah Dibayar</div>
        <div class="value green">Rp {{ number_format($statistik['total_terbayar'], 0, ',', '.') }}</div>
    </div>
    <div class="summary-cell">
        <div class="label">Lunas / Belum</div>
        <div class="value">{{ $statistik['jumlah_lunas'] }} / <span style="color:#e67e22;">{{ $statistik['jumlah_belum'] }}</span></div>
    </div>
</div>

@php $totTagihan = 0; $totBayar = 0; @endphp

<table>
    <thead>
        <tr>
            <th width="4%" class="text-center">No</th>
            <th width="10%">No. Transaksi</th>
            <th width="9%">Tanggal</th>
            <th>Pelanggan</th>
            <th width="14%" class="text-right">Total Tagihan</th>
            <th width="14%" class="text-right">Dibayar</th>
            <th width="10%" class="text-center">Metode</th>
            <th width="9%" class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $i => $row)
        @php $totTagihan += $row['total_tagihan']; $totBayar += $row['jumlah_bayar']; @endphp
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>#{{ str_pad($row['id_transaksi'], 6, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $row['tanggal'] }}</td>
            <td>{{ $row['pelanggan'] }}</td>
            <td class="text-right">Rp {{ number_format($row['total_tagihan'], 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($row['jumlah_bayar'], 0, ',', '.') }}</td>
            <td class="text-center">{{ strtoupper($row['metode_bayar']) }}</td>
            <td class="text-center">
                @if($row['status_bayar'] === 'lunas')
                    <span class="badge-lunas">LUNAS</span>
                @else
                    <span class="badge-belum">BELUM</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center">Tidak ada data</td></tr>
        @endforelse
    </tbody>
    @if(count($rows) > 0)
    <tfoot>
        <tr class="grand-total">
            <td colspan="4" class="text-right">Total Keseluruhan</td>
            <td class="text-right">Rp {{ number_format($totTagihan, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($totBayar, 0, ',', '.') }}</td>
            <td colspan="2"></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="footer">
    Dicetak pada: {{ $tanggal_cetak }} | Sofia Laundry Management System
</div>

</body>
</html>
