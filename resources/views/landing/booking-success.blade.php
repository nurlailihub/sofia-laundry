<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil — Laundry Sofia</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #2BB1B1;
            --secondary: #E0F2F1;
            --tertiary: #005F73;
            --neutral-bg: #F8FAFC;
            --dark-bg: #2D3E3A;
        }

        body {
            background: var(--neutral-bg);
            font-family: 'Inter', sans-serif;
            color: #2D3E3A;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
        }

        .success-icon {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--tertiary), var(--primary));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 2rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 95, 115, 0.2);
        }

        .booking-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 12px 35px rgba(0,95,115,.08);
            background: #ffffff;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: .65rem 0;
            border-bottom: 1px solid #E2E8F0;
        }

        .detail-row:last-child { border-bottom: none; }

        .btn-tertiary {
            background-color: var(--tertiary);
            color: #ffffff;
            font-weight: 700;
            border-radius: 9999px;
            padding: 0.75rem 1.5rem;
            border: none;
            text-decoration: none;
        }

        .btn-tertiary:hover {
            background-color: #004B5B;
            color: #ffffff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-decoration-none" href="{{ route('landing.index') }}" style="color: var(--tertiary); font-family: 'Montserrat', sans-serif;">
            <i class="fas fa-tshirt me-2"></i>Laundry Sofia
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card booking-card p-4 p-md-5 text-center">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="fw-bold mb-2 text-dark">Booking Berhasil!</h3>
                <p class="text-muted mb-4">Booking Anda telah kami terima dan sedang diproses.</p>

                <div class="alert text-start rounded-3 mb-4" style="background-color: var(--secondary); color: var(--tertiary); border: 1px solid rgba(0,95,115,0.15);">
                    <i class="fas fa-info-circle me-2"></i>
                    Tim Laundry Sofia akan segera menghubungi Anda melalui WhatsApp untuk konfirmasi penjemputan.
                </div>

                <div class="card bg-light border-0 rounded-4 p-4 text-start mb-4">
                    <h6 class="fw-bold mb-3 text-dark">Detail Booking</h6>
                    <div class="detail-row">
                        <span class="text-muted">Nomor Booking</span>
                        <span class="fw-bold" style="color: var(--tertiary);">{{ $booking->kode_reservasi ?? '#' . str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-muted">Nama</span>
                        <span class="fw-semibold text-dark">{{ $booking->pelanggan->nama_pelanggan }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-muted">No. HP</span>
                        <span>{{ $booking->pelanggan->no_hp }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-muted">Layanan</span>
                        <span>{{ $booking->layanan->nama_layanan }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="text-muted">Tanggal</span>
                        <span>{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d F Y') }}</span>
                    </div>
                    @if ($booking->waktu_booking)
                    <div class="detail-row">
                        <span class="text-muted">Waktu</span>
                        <span>{{ $booking->waktu_booking }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="text-muted">Antar-Jemput</span>
                        <span>
                            @switch($booking->tipe_antar_jemput)
                                @case('none') Antar Sendiri @break
                                @case('pickup') Jemput ke Rumah @break
                                @case('delivery') Antar ke Rumah @break
                                @case('both') Jemput & Antar @break
                            @endswitch
                        </span>
                    </div>
                    @if ($booking->estimasi_berat)
                    <div class="detail-row">
                        <span class="text-muted">Estimasi Berat</span>
                        <span>{{ $booking->estimasi_berat }} kg</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Konfirmasi</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('landing.cek-status') }}" class="btn btn-tertiary">
                        <i class="fas fa-search me-2"></i>Cek Status Cucian
                    </a>
                    <a href="{{ route('landing.index') }}" class="btn btn-outline-secondary rounded-pill py-2">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
