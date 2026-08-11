<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pelanggan - Sofia Laundry</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }

        .header { text-align: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 3px double #2c3e50; }
        .header .logo { font-size: 22px; font-weight: bold; color: #2c3e50; letter-spacing: 2px; }
        .header .logo i { font-size: 24px; margin-right: 5px; }
        .header .tagline { font-size: 11px; color: #7f8c8d; margin-top: 2px; }
        .header .alamat { font-size: 9px; color: #95a5a6; margin-top: 3px; }
        .header h2 { font-size: 14px; color: #2c3e50; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px; }

        .info { margin-bottom: 12px; background: #f8f9fa; padding: 8px 12px; border-left: 4px solid #3498db; }
        .info p { margin: 2px 0; font-size: 10px; }
        .info strong { color: #2c3e50; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 10px; }
        table th, table td { border: 1px solid #bdc3c7; padding: 5px 6px; text-align: left; }
        table th { background-color: #2c3e50; color: #fff; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        table tr:nth-child(even) { background-color: #f8f9fa; }
        table tr:hover { background-color: #eef2f7; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .statistik { margin-top: 15px; padding: 10px 12px; background: #f8f9fa; border: 1px solid #bdc3c7; }
        .statistik h4 { font-size: 12px; color: #2c3e50; margin-bottom: 8px; border-bottom: 1px solid #dee2e6; padding-bottom: 5px; }
        .statistik table { font-size: 10px; }
        .statistik table td { border: none; padding: 3px 8px; }

        .footer { margin-top: 25px; text-align: right; font-size: 9px; color: #95a5a6; border-top: 1px solid #dee2e6; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SOFIA LAUNDRY</div>
        <div class="tagline">Laundry Management System</div>
        <div class="alamat">Jl. Contoh Alamat No. 123, Kota | Telp: 0812-3456-7890</div>
        <h2>Laporan Data Pelanggan</h2>
    </div>

    <div class="info">
        <p><strong>Filter Nama Pelanggan:</strong> {{ $nama_filter }}</p>
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
        <p><strong>Tanggal Cetak:</strong> {{ $tanggal_cetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Pelanggan</th>
                <th>No. HP/WhatsApp</th>
                <th>Alamat</th>
                <th class="text-center">Total Transaksi</th>
                <th class="text-right">Total Pendapatan</th>
                <th class="text-center">Transaksi Terakhir</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pelanggans) > 0)
                @foreach($pelanggans as $index => $pelanggan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pelanggan->nama_pelanggan }}</td>
                    <td>{{ $pelanggan->no_hp }}</td>
                    <td>{{ $pelanggan->alamat }}</td>
                    <td class="text-center">{{ $pelanggan->total_transaksi ?? 0 }}</td>
                    <td class="text-right">Rp {{ number_format($pelanggan->total_pendapatan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($pelanggan->transaksi_terakhir)
                            {{ \Carbon\Carbon::parse($pelanggan->transaksi_terakhir)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if(($pelanggan->total_transaksi ?? 0) > 0)
                            Aktif
                        @else
                            Nonaktif
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data pelanggan</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="statistik">
        <h4>Ringkasan Statistik</h4>
        <table>
            <tr>
                <td><strong>Total Pelanggan:</strong></td>
                <td>{{ $statistik['total_pelanggan'] }}</td>
                <td><strong>Pelanggan Aktif:</strong></td>
                <td>{{ $statistik['pelanggan_aktif'] }}</td>
            </tr>
            <tr>
                <td><strong>Pelanggan Nonaktif:</strong></td>
                <td>{{ $statistik['pelanggan_nonaktif'] }}</td>
                <td><strong>Total Transaksi:</strong></td>
                <td>{{ $statistik['total_transaksi_keseluruhan'] }}</td>
            </tr>
            <tr>
                <td><strong>Total Pendapatan:</strong></td>
                <td colspan="3">Rp {{ number_format($statistik['total_pendapatan_keseluruhan'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }} | Sofia Laundry Management System</p>
    </div>
</body>
</html>
