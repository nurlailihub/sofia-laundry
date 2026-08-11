<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Booking {{ $booking->kode_reservasi ?? '#' . str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11pt; color: #1f2937; background: white; }
        .page { width: 100%; padding: 20px; }

        .header { background: #1d4ed8; color: white; padding: 18px 20px; border-radius: 8px 8px 0 0; margin-bottom: 0; }
        .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
        .brand-name { font-size: 16pt; font-weight: 700; }
        .brand-sub { font-size: 8.5pt; opacity: .8; margin-top: 2px; }
        .faktur-num { font-size: 10pt; font-weight: 700; text-align: right; }
        .faktur-date { font-size: 8pt; opacity: .75; text-align: right; margin-top: 2px; }

        .status-bar { background: #1e3a8a; padding: 6px 20px; color: white; font-size: 9pt; letter-spacing: 1px; }

        .body { padding: 16px 20px; }

        .info-row { display: flex; gap: 20px; margin-bottom: 14px; }
        .info-box { flex: 1; }
        .info-label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; margin-bottom: 3px; }
        .info-value { font-size: 10pt; font-weight: 600; }
        .info-sub { font-size: 8.5pt; color: #6b7280; margin-top: 1px; }

        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 12px 0; }

        table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        table th { background: #f8fafc; padding: 6px 8px; text-align: left; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .4px; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
        table td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .total-section { margin-top: 10px; padding: 10px 12px; background: #f8fafc; border-radius: 6px; border: 1px solid #e5e7eb; }
        .total-row { display: flex; justify-content: space-between; padding: 3px 0; font-size: 9.5pt; }
        .total-row.grand { font-size: 13pt; font-weight: 800; color: #1d4ed8; padding-top: 8px; margin-top: 6px; border-top: 2px solid #e5e7eb; }

        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%) rotate(-30deg); font-size: 60pt; font-weight: 900; color: rgba(37,99,235,.06); letter-spacing: 10px; pointer-events: none; z-index: -1; }

        .note-box { margin-top: 12px; padding: 10px 12px; background: #fef3c7; border: 1px solid #fde68a; border-radius: 6px; font-size: 8.5pt; color: #92400e; }

        .footer { margin-top: 14px; padding-top: 10px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 8pt; color: #9ca3af; }
    </style>
</head>
<body>
<div class="page">

    @if ($booking->status === 'confirmed')
    <div class="watermark">BOOKING</div>
    @endif

    <div class="header">
        <div class="header-top">
            <div>
                <div class="brand-name">&#9897; Sofia Laundry</div>
                <div class="brand-sub">Jl. Contoh No. 123, Kota Anda &nbsp;|&nbsp; +62 812-3456-7890</div>
            </div>
            <div>
                <div class="faktur-num">{{ $booking->kode_reservasi ?? '#' . str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="faktur-date">{{ $booking->created_at->format('d F Y, H:i') }}</div>
            </div>
        </div>
    </div>

    @php
        $statusBar = match($booking->status) {
            'pending' => 'MENUNGGU KONFIRMASI',
            'confirmed' => 'DIKONFIRMASI',
            'completed' => 'SELESAI',
            'cancelled' => 'DIBATALKAN',
            default => 'UNKNOWN',
        };
    @endphp
    <div class="status-bar">
        FAKTUR BOOKING &nbsp;|&nbsp; STATUS: {{ $statusBar }}
    </div>

    <div class="body">
        <div class="info-row">
            <div class="info-box">
                <div class="info-label">Data Pelanggan</div>
                <div class="info-value">{{ $booking->pelanggan->nama_pelanggan ?? '-' }}</div>
                <div class="info-sub">{{ $booking->pelanggan->no_hp ?? '' }}</div>
                <div class="info-sub">{{ $booking->pelanggan->alamat ?? '' }}</div>
            </div>
            <div class="info-box" style="text-align:right;">
                <div class="info-label">Info Booking</div>
                <div class="info-value">{{ $booking->layanan->nama_layanan ?? '-' }}</div>
                <div class="info-sub">Tanggal: {{ $booking->tanggal_booking->format('d/m/Y') }}</div>
                @if($booking->waktu_booking)
                <div class="info-sub">Waktu: {{ $booking->waktu_booking }}</div>
                @endif
                @php $tipeLabel = ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'] @endphp
                <div class="info-sub">Antar/Jemput: {{ $tipeLabel[$booking->tipe_antar_jemput] ?? '-' }}</div>
            </div>
        </div>

        <hr class="divider">

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-center">Berat (Estimasi)</th>
                    <th class="text-right">Harga/kg</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $berat = $booking->estimasi_berat ?? 0;
                    $harga = $booking->layanan->harga_per_kg ?? 0;
                    $subtotal = $berat * $harga;
                @endphp
                <tr>
                    <td>{{ $booking->layanan->nama_layanan ?? '-' }}</td>
                    <td class="text-center">{{ $berat }} kg</td>
                    <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                @if ($booking->biaya_antar_jemput > 0)
                <tr>
                    <td colspan="3">Biaya Antar/Jemput</td>
                    <td class="text-right">Rp {{ number_format($booking->biaya_antar_jemput, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        @php
            $totalEstimasi = $subtotal + ($booking->biaya_antar_jemput ?? 0);
        @endphp

        <div class="total-section">
            <div class="total-row">
                <span>Total Estimasi</span>
                <span>Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</span>
            </div>
            @if ($booking->dp_bayar > 0)
            <div class="total-row" style="color:#065f46;">
                <span>DP Dibayar</span>
                <span>Rp {{ number_format($booking->dp_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="color:#ef4444;">
                <span>Sisa Bayar</span>
                <span>Rp {{ number_format($booking->sisa_bayar ?? 0, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row grand">
                <span>{{ $booking->status === 'confirmed' ? 'DIKONFIRMASI' : 'MENUNGGU' }}</span>
                <span>Rp {{ number_format($totalEstimasi, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="note-box">
            <strong>Penting:</strong> Berat final akan dihitung ulang setelah cucian ditimbang di tempat kami.
            Total yang harus dibayar sesuai dengan berat aktual cucian.
        </div>
    </div>

    <div class="footer">
        Dicetak {{ now()->format('d F Y H:i') }} &nbsp;|&nbsp; Terima kasih telah menggunakan layanan Sofia Laundry
    </div>

</div>
</body>
</html>
