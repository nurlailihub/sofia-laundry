@extends('layouts.admin')

@section('title', 'Dashboard Overview — Laundry Sofia')

@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Pantau kinerja, transaksi, dan status cucian secara real-time')

@push('styles')
<style>
    :root {
        --primary-sofia: #2BB1B1;
        --secondary-sofia: #E0F2F1;
        --tertiary-sofia: #005F73;
        --dark-sofia: #2D3E3A;
    }

    /* Hero Banner */
    .dashboard-hero {
        background: linear-gradient(135deg, var(--tertiary-sofia) 0%, #004B5B 100%);
        border-radius: 24px;
        padding: 2.2rem;
        color: #ffffff;
        margin-bottom: 2rem;
        box-shadow: 0 14px 35px rgba(0, 95, 115, 0.18);
        position: relative;
        overflow: hidden;
    }

    .dashboard-hero::after {
        content: '';
        position: absolute;
        right: -30px;
        bottom: -30px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-greeting {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .hero-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.98rem;
        max-width: 620px;
        margin-bottom: 1.5rem;
    }

    .hero-actions .btn-action {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        font-weight: 600;
        padding: 0.55rem 1.25rem;
        border-radius: 9999px;
        font-size: 0.88rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.25s ease;
    }

    .hero-actions .btn-action:hover {
        background: #ffffff;
        color: var(--tertiary-sofia);
        transform: translateY(-2px);
    }

    .hero-date-badge {
        background: rgba(0, 0, 0, 0.2);
        padding: 0.5rem 1.1rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* KPI Stat Cards */
    .kpi-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        padding: 1.4rem;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.25rem;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 95, 115, 0.1);
        border-color: var(--primary-sofia);
    }

    .kpi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
    }

    .kpi-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .kpi-icon-teal { background-color: #E0F2F1; color: #005F73; }
    .kpi-icon-green { background-color: #D1FAE5; color: #059669; }
    .kpi-icon-amber { background-color: #FEF3C7; color: #D97706; }
    .kpi-icon-rose { background-color: #FFE4E6; color: #E11D48; }
    .kpi-icon-purple { background-color: #EDE9FE; color: #7C3AED; }

    .kpi-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-value {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
        color: #0F172A;
        margin-bottom: 0.25rem;
    }

    .kpi-link {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--tertiary-sofia);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        margin-top: 0.5rem;
    }

    .kpi-link:hover {
        color: var(--primary-sofia);
    }

    /* Section Cards */
    .sofia-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .sofia-card-header {
        padding: 1.25rem 1.5rem;
        background: #ffffff;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sofia-card-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 1.05rem;
        color: #0F172A;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .table-sofia th {
        background: #F8FAFC;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        font-weight: 700;
        border-bottom: 1px solid #E2E8F0;
        padding: 0.85rem 1.2rem;
    }

    .table-sofia td {
        padding: 0.95rem 1.2rem;
        vertical-align: middle;
        font-size: 0.9rem;
        color: #334155;
    }

    /* User Profile Widget */
    .profile-widget {
        text-align: center;
        padding: 1.75rem 1.5rem;
        background: linear-gradient(180deg, #F8FAFC 0%, #ffffff 100%);
    }

    .profile-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: var(--tertiary-sofia);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1rem;
        box-shadow: 0 8px 20px rgba(0, 95, 115, 0.2);
    }
</style>
@endpush

@section('content')

<!-- Welcome Hero Banner -->
<div class="dashboard-hero">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-2">
        <div>
            <h1 class="hero-greeting">Selamat Datang, {{ auth()->user()->nama_user ?? 'Admin' }}! 👋</h1>
            <p class="hero-subtitle">
                Pantau kinerja operasional, arus kas pendapatan, dan status antrean pencucian Laundry Sofia hari ini secara real-time.
            </p>
        </div>
        <div class="hero-date-badge">
            <i class="far fa-calendar-alt"></i>
            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}
            &nbsp;·&nbsp;
            <i class="far fa-clock ms-1"></i>
            <span id="liveClockDisplay">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
        </div>
    </div>
    
    <div class="hero-actions d-flex flex-wrap gap-2">
        <a href="{{ route('admin.transaksis.create') }}" class="btn-action">
            <i class="fas fa-plus-circle"></i> Buat Transaksi Baru
        </a>
        <a href="{{ route('admin.monitoring.index') }}" class="btn-action">
            <i class="fas fa-satellite-dish"></i> Status Monitoring
        </a>
        <a href="{{ route('admin.pelanggans.create') }}" class="btn-action">
            <i class="fas fa-user-plus"></i> Tambah Pelanggan
        </a>
        <a href="{{ route('admin.laporan.transaksi.index') }}" class="btn-action">
            <i class="fas fa-file-invoice-dollar"></i> Laporan Keuangan
        </a>
    </div>
</div>

<!-- Row 1: KPI Stats Grid -->
<div class="row">
    <!-- Card 1 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Pendapatan Hari Ini</span>
                <div class="kpi-icon-box kpi-icon-teal">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="kpi-value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <a href="{{ route('admin.laporan.transaksi.index') }}" class="kpi-link">
                Rincian Laporan <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Pendapatan Bulan Ini</span>
                <div class="kpi-icon-box kpi-icon-green">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="kpi-value">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
            <a href="{{ route('admin.laporan.transaksi.index') }}" class="kpi-link">
                Lihat Rekap <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Masuk Hari Ini</span>
                <div class="kpi-icon-box kpi-icon-amber">
                    <i class="fas fa-tshirt"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $cucianMasukHariIni }} <small class="text-muted fs-6 fw-normal">Pesanan</small></div>
            <a href="{{ route('admin.transaksis.index') }}" class="kpi-link">
                Kelola Transaksi <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Sedang Diproses</span>
                <div class="kpi-icon-box kpi-icon-rose">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $cucianProses }} <small class="text-muted fs-6 fw-normal">Antrean</small></div>
            <a href="{{ route('admin.monitoring.index') }}" class="kpi-link">
                Update Status <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Row 2: Secondary KPIs -->
<div class="row">
    <!-- Card 5 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Siap Diambil</span>
                <div class="kpi-icon-box kpi-icon-green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $cucianSelesai }} <small class="text-muted fs-6 fw-normal">Cucian</small></div>
            <a href="{{ route('admin.monitoring.index') }}" class="kpi-link">
                Lihat Monitoring <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 6 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Sudah Diambil</span>
                <div class="kpi-icon-box kpi-icon-purple">
                    <i class="fas fa-flag-checkered"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $cucianDiambil }} <small class="text-muted fs-6 fw-normal">Selesai</small></div>
            <a href="{{ route('admin.transaksis.index') }}" class="kpi-link">
                Riwayat <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 7 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Total Pelanggan</span>
                <div class="kpi-icon-box kpi-icon-teal">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $totalPelanggan }} <small class="text-muted fs-6 fw-normal">Member</small></div>
            <a href="{{ route('admin.pelanggans.index') }}" class="kpi-link">
                Data Pelanggan <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 8 -->
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-label">Status System</span>
                <div class="kpi-icon-box kpi-icon-teal">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <div class="kpi-value text-success fs-4">Online <small class="text-muted fs-6 fw-normal">Operational</small></div>
            <span class="text-muted small">WhatsApp Gateway Connected</span>
        </div>
    </div>
</div>

<!-- Status Laundry Aktif Monitoring Table -->
<div class="row">
    <div class="col-12">
        <div class="sofia-card">
            <div class="sofia-card-header">
                <h3 class="sofia-card-title">
                    <i class="fas fa-satellite-dish" style="color: var(--tertiary-sofia);"></i>
                    Status Laundry Aktif (Monitoring Real-time)
                </h3>
                <a href="{{ route('admin.monitoring.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                    <i class="fas fa-expand me-1"></i> Lihat Semua Monitoring
                </a>
            </div>
            <div class="card-body p-0">
                @if ($monitoringRingkas->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-sofia mb-0">
                        <thead>
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>Berat (kg)</th>
                                <th>Tanggal Masuk</th>
                                <th>Status Proses</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monitoringRingkas as $trx)
                            @php
                                $color = $statusDetailColors[$trx->status_detail] ?? 'secondary';
                                $icon  = $statusDetailIcons[$trx->status_detail] ?? 'fas fa-circle';
                                $label = $statusDetailLabels[$trx->status_detail] ?? '-';
                            @endphp
                            <tr>
                                <td><strong style="color: var(--tertiary-sofia);">#{{ str_pad($trx->id_transaksi, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</div>
                                    <small class="text-muted">{{ $trx->pelanggan->no_hp ?? '' }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark border px-2 py-1">{{ $trx->total_berat }} kg</span></td>
                                <td>{{ $trx->tanggal_masuk->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $color }} px-3 py-1.5 rounded-pill font-weight-normal">
                                        <i class="{{ $icon }} me-1"></i>{{ $label }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.monitoring.index') }}" class="btn btn-xs btn-primary rounded-pill px-3 py-1" style="background-color: var(--tertiary-sofia); border:none;">
                                        <i class="fas fa-edit me-1"></i> Update
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success opacity-50"></i>
                    <h6 class="fw-bold text-dark mb-1">Semua cucian aktif telah selesai!</h6>
                    <p class="small mb-0">Tidak ada antrean laundry yang sedang diproses saat ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bottom Section: Pelanggan Terbaru & System Info -->
<div class="row">
    <!-- Pelanggan Terbaru -->
    <div class="col-lg-7">
        <div class="sofia-card">
            <div class="sofia-card-header">
                <h3 class="sofia-card-title">
                    <i class="fas fa-users" style="color: var(--tertiary-sofia);"></i>
                    Pelanggan Terbaru Pendaftar
                </h3>
                <a href="{{ route('admin.pelanggans.index') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: var(--tertiary-sofia);">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sofia mb-0">
                        <thead>
                            <tr>
                                <th>Nama Pelanggan</th>
                                <th>No. WhatsApp</th>
                                <th>Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelangganTerbaru as $pelanggan)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $pelanggan->nama_pelanggan }}</td>
                                <td>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pelanggan->no_hp) }}" target="_blank" class="btn btn-xs btn-outline-success rounded-pill px-2 py-0.5">
                                        <i class="fab fa-whatsapp me-1"></i> {{ $pelanggan->no_hp }}
                                    </a>
                                </td>
                                <td><span class="text-muted small">{{ \Carbon\Carbon::parse($pelanggan->created_at)->diffForHumans() }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada data pelanggan baru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Account & System Card -->
    <div class="col-lg-5">
        <div class="sofia-card">
            <div class="profile-widget">
                <div class="profile-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1" style="font-family: 'Montserrat', sans-serif;">{{ auth()->user()->nama_user ?? 'Administrator' }}</h4>
                <span class="badge rounded-pill bg-success px-3 py-1.5 mb-3">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</span>
                <p class="text-muted small mb-3">Laundry Management System Active Session</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Home</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-sm btn-danger rounded-pill px-3">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const hrs = String(now.getHours()).padStart(2, '0');
        const mins = String(now.getMinutes()).padStart(2, '0');
        const secs = String(now.getSeconds()).padStart(2, '0');
        const clockEl = document.getElementById('liveClockDisplay');
        if (clockEl) clockEl.innerText = `${hrs}:${mins}:${secs}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush
