<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Per Tahun - Sofia Laundry</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; font-size:11px; color:#333; }
.header { text-align:center; margin-bottom:15px; padding-bottom:10px; border-bottom:3px double #2c3e50; }
.header .logo { font-size:22px; font-weight:bold; color:#2c3e50; letter-spacing:2px; }
.header .tagline { font-size:11px; color:#7f8c8d; margin-top:2px; }
.header .alamat { font-size:9px; color:#95a5a6; margin-top:3px; }
.header h2 { font-size:14px; color:#2c3e50; margin-top:8px; text-transform:uppercase; letter-spacing:1px; }
.info { margin-bottom:12px; background:#f8f9fa; padding:8px 12px; border-left:4px solid #3498db; }
.info p { margin:2px 0; font-size:10px; }
.info strong { color:#2c3e50; }
table { width:100%; border-collapse:collapse; margin-top:8px; font-size:11px; }
table th, table td { border:1px solid #bdc3c7; padding:6px 8px; }
table th { background:#2c3e50; color:#fff; font-size:9px; text-transform:uppercase; }
table tr:nth-child(even) { background:#f8f9fa; }
.text-right { text-align:right; }
.text-center { text-align:center; }
.grand-total td { font-weight:bold; background:#ecf0f1 !important; border-top:2px solid #2c3e50; }
.footer { margin-top:20px; text-align:right; font-size:9px; color:#95a5a6; border-top:1px solid #dee2e6; padding-top:5px; }
</style>
</head>
<body>
<div class="header">
    <div class="logo">SOFIA LAUNDRY</div>
    <div class="tagline">Laundry Management System</div>
    <div class="alamat">Jl. Contoh Alamat No. 123, Kota | Telp: 0812-3456-7890</div>
    <h2>Laporan Per Tahun</h2>
</div>

<div class="info">
    <p><strong>Tahun:</strong> {{ $tahun_filter }}</p>
    <p><strong>Tanggal Cetak:</strong> {{ $tanggal_cetak }}</p>
</div>

@php $grandTotalTrx = 0; $grandTotalPend = 0; @endphp

<table>
    <thead>
        <tr>
            <th width="8%" class="text-center">No</th>
            <th>Bulan</th>
            <th class="text-right">Total Transaksi</th>
            <th class="text-right">Total Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($perBulan as $i => $row)
        @php $grandTotalTrx += $row['total_transaksi']; $grandTotalPend += $row['total_pendapatan']; @endphp
        <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td>{{ $row['bulan'] }}</td>
            <td class="text-right">{{ $row['total_transaksi'] }}</td>
            <td class="text-right">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
        @endforelse
    </tbody>
    @if(count($perBulan) > 0)
    <tfoot>
        <tr class="grand-total">
            <td colspan="2" class="text-right">Jumlah Keseluruhan</td>
            <td class="text-right">{{ $grandTotalTrx }}</td>
            <td class="text-right">Rp {{ number_format($grandTotalPend, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="footer">
    <p>Dicetak pada: {{ $tanggal_cetak }} | Sofia Laundry Management System</p>
</div>
</body>
</html>
