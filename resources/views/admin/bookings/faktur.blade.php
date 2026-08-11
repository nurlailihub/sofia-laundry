@extends('layouts.admin')

@section('title', 'Faktur Booking')
@section('page-title', 'Faktur Booking')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Kelola Booking</a></li>
<li class="breadcrumb-item active">Faktur</li>
@endsection

@push('styles')
<style>
.faktur-wrap {
    max-width: 680px;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,.10);
    overflow: hidden;
}
.faktur-header {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    color: white;
    padding: 2rem 2.5rem 1.5rem;
}
.faktur-body { padding: 2rem 2.5rem; }
.faktur-footer { padding: 1.25rem 2.5rem; background: #f8fafc; border-top: 1px solid #e5e7eb; }
.detail-row { display: flex; justify-content: space-between; padding: .45rem 0; border-bottom: 1px solid #f1f5f9; font-size: .9rem; }
.detail-row:last-child { border-bottom: none; }
.watermark { position: relative; }
.watermark::after {
    content: 'BOOKING';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%) rotate(-30deg);
    font-size: 4rem;
    font-weight: 900;
    color: rgba(37,99,235,.08);
    pointer-events: none;
    letter-spacing: 8px;
}
</style>
@endpush

@section('content')

<div class="faktur-wrap {{ $booking->status === 'confirmed' ? 'watermark' : '' }}">

    <div class="faktur-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div style="font-size:1.4rem;font-weight:800;letter-spacing:-0.5px;">
                    <i class="fas fa-tshirt mr-2"></i>Sofia Laundry
                </div>
                <div style="opacity:.75;font-size:.85rem;margin-top:.25rem;">Jl. Contoh No. 123, Kota Anda</div>
                <div style="opacity:.75;font-size:.85rem;">+62 812-3456-7890</div>
            </div>
            <div class="text-right">
                <div style="font-size:.8rem;opacity:.7;text-transform:uppercase;letter-spacing:.5px;">Nomor Booking</div>
                <div style="font-size:1.1rem;font-weight:700;">{{ $booking->kode_reservasi ?? '#' . str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) }}</div>
                <div style="font-size:.8rem;opacity:.7;margin-top:.25rem;">
                    {{ $booking->created_at->format('d F Y, H:i') }}
                </div>
            </div>
        </div>
        <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.2);">
            @php
                $statusBadge = match($booking->status) {
                    'pending' => ['Menunggu Konfirmasi', 'fas fa-clock', 'warning'],
                    'confirmed' => ['Dikonfirmasi', 'fas fa-check-circle', 'success'],
                    'completed' => ['Selesai', 'fas fa-flag-checkered', 'success'],
                    'cancelled' => ['Dibatalkan', 'fas fa-times-circle', 'danger'],
                    default => ['Unknown', 'fas fa-question', 'secondary'],
                };
            @endphp
            <span class="badge badge-{{ $statusBadge[2] }}" style="font-size:.85rem;padding:.5rem 1rem;">
                <i class="{{ $statusBadge[1] }} mr-1"></i> {{ strtoupper($statusBadge[0]) }}
            </span>
        </div>
    </div>

    <div class="faktur-body">
        <div class="row mb-4">
            <div class="col-6">
                <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;margin-bottom:.5rem;">Data Pelanggan</div>
                <div style="font-weight:700;font-size:1rem;">{{ $booking->pelanggan->nama_pelanggan ?? '-' }}</div>
                <div style="color:#6b7280;font-size:.875rem;">{{ $booking->pelanggan->no_hp ?? '' }}</div>
                <div style="color:#6b7280;font-size:.875rem;">{{ $booking->pelanggan->alamat ?? '' }}</div>
            </div>
            <div class="col-6 text-right">
                <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;margin-bottom:.5rem;">Info Booking</div>
                <div style="font-size:.875rem;">
                    <span class="text-muted">Layanan:</span>
                    <strong>{{ $booking->layanan->nama_layanan ?? '-' }}</strong>
                </div>
                <div style="font-size:.875rem;">
                    <span class="text-muted">Tanggal:</span>
                    {{ $booking->tanggal_booking->format('d/m/Y') }}
                    @if($booking->waktu_booking) {{ $booking->waktu_booking }} @endif
                </div>
                <div style="font-size:.875rem;">
                    @php $tipeLabel = ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'] @endphp
                    <span class="text-muted">Antar/Jemput:</span>
                    {{ $tipeLabel[$booking->tipe_antar_jemput] ?? '-' }}
                </div>
            </div>
        </div>

        <table class="table table-borderless mb-0" style="font-size:.9rem;">
            <thead style="background:#f8fafc;border-radius:8px;">
                <tr>
                    <th style="padding:.6rem .75rem;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;">Layanan</th>
                    <th class="text-center" style="padding:.6rem .75rem;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;">
                        Berat
                        @if($booking->status === 'confirmed' && $booking->transaksi) (Asli) @else (Estimasi) @endif
                    </th>
                    <th class="text-right" style="padding:.6rem .75rem;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;">Harga/kg</th>
                    <th class="text-right" style="padding:.6rem .75rem;font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @if($booking->status === 'confirmed' && $booking->transaksi && $booking->transaksi->detailTransaksi->count())
                    {{-- Tampilkan detail transaksi nyata setelah konfirmasi --}}
                    @php $totalHargaAsli = 0; $totalBeratAsli = 0; @endphp
                    @foreach($booking->transaksi->detailTransaksi as $detail)
                    @php
                        $totalHargaAsli += $detail->subtotal;
                        $totalBeratAsli += $detail->berat;
                    @endphp
                    <tr>
                        <td style="padding:.6rem .75rem;">{{ $detail->layanan->nama_layanan ?? '-' }}</td>
                        <td class="text-center" style="padding:.6rem .75rem;">
                            <strong>{{ number_format($detail->berat, 2, ',', '.') }} kg</strong>
                        </td>
                        <td class="text-right" style="padding:.6rem .75rem;">
                            Rp {{ number_format($detail->layanan->harga_per_kg ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="padding:.6rem .75rem;">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                    @php
                        $subtotal = $totalHargaAsli;
                        $berat    = $totalBeratAsli;
                    @endphp
                @else
                    {{-- Estimasi sebelum dikonfirmasi --}}
                    @php
                        $berat    = $booking->estimasi_berat ?? 0;
                        $harga    = $booking->layanan->harga_per_kg ?? 0;
                        $subtotal = $berat * $harga;
                    @endphp
                    <tr>
                        <td style="padding:.6rem .75rem;">{{ $booking->layanan->nama_layanan ?? '-' }}</td>
                        <td class="text-center" style="padding:.6rem .75rem;">{{ $berat }} kg</td>
                        <td class="text-right" style="padding:.6rem .75rem;">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                        <td class="text-right" style="padding:.6rem .75rem;">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endif

                @if ($booking->biaya_antar_jemput > 0)
                <tr>
                    <td style="padding:.6rem .75rem;" colspan="3">
                        <i class="fas fa-truck mr-1 text-muted"></i>
                        Biaya Antar/Jemput
                    </td>
                    <td class="text-right" style="padding:.6rem .75rem;">
                        Rp {{ number_format($booking->biaya_antar_jemput, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                @if($booking->status === 'confirmed' && $booking->transaksi)
                <tr style="border-top:2px solid #e5e7eb;">
                    <td colspan="3" class="text-right" style="padding:.75rem;font-weight:600;">
                        Total Berat Asli: <span class="text-primary">{{ number_format($berat, 2, ',', '.') }} kg</span>
                        &nbsp;|&nbsp; Total Harga
                    </td>
                    <td class="text-right" style="padding:.75rem;font-weight:700;">
                        Rp {{ number_format($subtotal + ($booking->biaya_antar_jemput ?? 0), 0, ',', '.') }}
                    </td>
                </tr>
                @else
                <tr style="border-top:2px solid #e5e7eb;">
                    <td colspan="3" class="text-right" style="padding:.75rem;font-weight:600;">Total Estimasi</td>
                    <td class="text-right" style="padding:.75rem;font-weight:700;">
                        Rp {{ number_format($subtotal + ($booking->biaya_antar_jemput ?? 0), 0, ',', '.') }}
                    </td>
                </tr>
                @endif
                @if ($booking->dp_bayar > 0)
                <tr>
                    <td colspan="3" class="text-right" style="padding:.5rem .75rem;color:#6b7280;">DP Dibayar</td>
                    <td class="text-right" style="padding:.5rem .75rem;color:#10b981;font-weight:600;">
                        Rp {{ number_format($booking->dp_bayar, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right" style="padding:.5rem .75rem;color:#6b7280;">Sisa Bayar</td>
                    <td class="text-right" style="padding:.5rem .75rem;color:#ef4444;font-weight:600;">
                        Rp {{ number_format($booking->sisa_bayar ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
            </tfoot>
        </table>

        <div class="mt-4 p-3 rounded" style="background:#f8fafc;border:1px solid #e5e7eb;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @php $icons = \App\Models\Pembayaran::$metodeIcons; $labels = \App\Models\Pembayaran::$metodeLabels; @endphp
                    <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;">Metode Pembayaran</div>
                    <div style="font-weight:600;margin-top:.25rem;">
                        <i class="{{ $icons[$booking->metode_bayar ?? 'cash'] ?? 'fas fa-money-bill' }} mr-2"></i>
                        {{ $labels[$booking->metode_bayar ?? 'cash'] ?? $booking->metode_bayar }}
                    </div>
                </div>
                <div class="text-right">
                    <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;">
                        @if($booking->status === 'confirmed' && $booking->transaksi) Total Tagihan Asli @else Total Estimasi @endif
                    </div>
                    <div style="font-size:1.5rem;font-weight:800;color:#1d4ed8;">
                        Rp {{ number_format($subtotal + ($booking->biaya_antar_jemput ?? 0), 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @if ($booking->catatan)
            <div class="mt-2 pt-2" style="border-top:1px solid #e5e7eb;font-size:.8rem;color:#6b7280;">
                <i class="fas fa-comment mr-1"></i>{{ $booking->catatan }}
            </div>
            @endif
        </div>

        @if ($booking->status === 'pending')
        <div class="mt-3 p-3 rounded" style="background:#fef3c7;border:1px solid #fde68a;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                <div style="font-size:.85rem;color:#92400e;">
                    Booking ini menunggu konfirmasi. Berat final akan ditimbang saat cucian diterima.
                </div>
            </div>
        </div>
        @endif

        @if ($booking->status === 'confirmed' && $booking->transaksi)
        <div class="mt-3 p-3 rounded" style="background:#d1fae5;border:1px solid #6ee7b7;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle text-success mr-2"></i>
                <div style="font-size:.85rem;color:#065f46;">
                    Booking sudah dikonfirmasi. Transaksi #{{ str_pad($booking->transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }} sudah dibuat.
                    @if ($booking->transaksi->pembayaran && $booking->transaksi->pembayaran->status_bayar !== 'lunas')
                    <br>Tagihan: <strong>Rp {{ number_format($booking->transaksi->pembayaran->jumlah_bayar, 0, ',', '.') }}</strong> —
                    <a href="{{ route('admin.pembayarans.create', $booking->transaksi->id_transaksi) }}" class="font-weight-bold">Catat Pembayaran</a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="faktur-footer d-flex justify-content-between align-items-center">
        <div style="font-size:.75rem;color:#9ca3af;">
            Dicetak: {{ now()->format('d F Y, H:i') }}
        </div>
        <div style="font-size:.75rem;color:#9ca3af;">Terima kasih telah menggunakan Sofia Laundry 🙏</div>
    </div>
</div>

<div class="d-flex justify-content-center gap-3 mt-4 mb-4">
    <a href="{{ route('admin.bookings.cetak', $booking->id_booking) }}" target="_blank"
        class="btn btn-primary px-4">
        <i class="fas fa-print mr-2"></i>Cetak / Download PDF
    </a>
    @if ($booking->status === 'pending')
    <a href="{{ route('admin.bookings.edit', $booking->id_booking) }}" class="btn btn-info px-4">
        <i class="fas fa-edit mr-2"></i>Edit Booking
    </a>
    @endif
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary px-4">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

@endsection
