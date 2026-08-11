@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')
@section('page-title', 'Dashboard Saya')

@section('content')

@php
$statusDetailLabels = \App\Models\Transaksi::$statusDetailLabels;
$statusDetailIcons  = \App\Models\Transaksi::$statusDetailIcons;
$statusDetailColors = \App\Models\Transaksi::$statusDetailColors;
$allSteps = array_keys($statusDetailLabels);
@endphp

@if (isset($transaksiAktifs) && $transaksiAktifs->isNotEmpty())

{{-- Judul section --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
        <i class="fas fa-tshirt me-2 text-primary"></i>Status Laundry Aktif
        <span class="badge rounded-pill ms-1 px-2"
              style="background:#2BB1B1;font-size:.75rem;">{{ $transaksiAktifs->count() }}</span>
    </h5>
    <a href="{{ route('customer.riwayat') }}" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-history me-1"></i>Semua Riwayat
    </a>
</div>

@foreach ($transaksiAktifs as $transaksiAktif)
@php
$currentIndex = array_search($transaksiAktif->status_detail, $allSteps);
$isSelesai    = in_array($transaksiAktif->status, ['selesai', 'diambil']);
$headerColor  = $isSelesai
    ? 'linear-gradient(135deg,#059669,#10b981)'
    : 'linear-gradient(135deg,#005F73,#2BB1B1)';
@endphp
<div class="card border-0 rounded-4 shadow-sm mb-3 overflow-hidden">
    <div class="card-header p-3 px-4" style="background:{{ $headerColor }};color:white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold" style="font-size:.95rem;">
                    <i class="fas fa-hashtag me-1" style="opacity:.7;font-size:.8rem;"></i>
                    {{ str_pad($transaksiAktif->id_transaksi, 6, '0', STR_PAD_LEFT) }}
                    @if($transaksiAktif->detailTransaksi->isNotEmpty())
                    &nbsp;·&nbsp;
                    <span style="font-size:.82rem;opacity:.9;">
                        {{ $transaksiAktif->detailTransaksi->map(fn($d) => $d->layanan->nama_layanan ?? '')->filter()->join(', ') }}
                    </span>
                    @endif
                </div>
                <div style="font-size:.78rem;opacity:.8;margin-top:.15rem;">
                    Masuk: {{ $transaksiAktif->tanggal_masuk->format('d/m/Y') }}
                    &nbsp;·&nbsp; {{ $transaksiAktif->total_berat }} kg
                    &nbsp;·&nbsp; Rp {{ number_format($transaksiAktif->total_harga, 0, ',', '.') }}
                </div>
            </div>
            <span class="badge bg-white fw-bold px-2 py-1" style="font-size:.75rem;color:#005F73;white-space:nowrap;">
                {{ $statusDetailLabels[$transaksiAktif->status_detail] ?? '-' }}
            </span>
        </div>
    </div>

    <div class="card-body p-3 px-4">
        {{-- Progress stepper --}}
        <div class="d-flex align-items-center overflow-auto pb-2" style="gap:0;">
            @foreach ($allSteps as $i => $step)
            @php
            $done    = $i < $currentIndex;
            $current = $i === $currentIndex;
            @endphp
            <div class="text-center flex-shrink-0" style="min-width:62px;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                    style="width:34px;height:34px;
                    background:{{ $current ? '#2563eb' : ($done ? '#10b981' : '#e5e7eb') }};
                    color:{{ ($current||$done) ? 'white' : '#9ca3af' }};
                    font-size:.78rem;">
                    @if($done)<i class="fas fa-check"></i>
                    @elseif($current)<i class="{{ $statusDetailIcons[$step] }}"></i>
                    @else<i class="{{ $statusDetailIcons[$step] }}" style="opacity:.35;"></i>
                    @endif
                </div>
                <div style="font-size:.58rem;line-height:1.2;
                    color:{{ $current ? '#2563eb' : ($done ? '#10b981' : '#9ca3af') }};
                    font-weight:{{ $current ? '700' : '400' }};">
                    {{ Str::limit($statusDetailLabels[$step], 10, '') }}
                </div>
            </div>
            @if(!$loop->last)
            <div style="flex:1;height:2px;min-width:6px;
                background:{{ $i < $currentIndex ? '#10b981' : '#e5e7eb' }};
                margin-bottom:22px;transition:all .3s;"></div>
            @endif
            @endforeach
        </div>

        @if($transaksiAktif->catatan_status)
        <div class="alert alert-info border-0 rounded-3 py-2 px-3 mb-2 mt-2" style="font-size:.82rem;">
            <i class="fas fa-comment me-1"></i>{{ $transaksiAktif->catatan_status }}
        </div>
        @endif

        {{-- Footer info --}}
        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
            <div style="font-size:.8rem;">
                @if($transaksiAktif->pembayaran)
                @if($transaksiAktif->pembayaran->status_bayar === 'lunas')
                <span class="badge bg-success-subtle text-success">
                    <i class="fas fa-check me-1"></i>Lunas
                </span>
                @else
                <span class="badge bg-warning-subtle text-warning">
                    <i class="fas fa-clock me-1"></i>Belum Lunas
                </span>
                @endif
                @endif
            </div>
            <div style="display:flex;gap:.5rem;">
                @if($transaksiAktif->pembayaran && $transaksiAktif->pembayaran->status_bayar !== 'lunas')
                <a href="{{ route('customer.transaksi.faktur', $transaksiAktif->id_transaksi) }}"
                   class="btn btn-sm btn-outline-primary" style="font-size:.78rem;">
                    <i class="fas fa-file-invoice me-1"></i>Faktur
                </a>
                @endif
                <a href="{{ route('customer.transaksi.detail', $transaksiAktif->id_transaksi) }}"
                   class="btn btn-sm btn-primary" style="font-size:.78rem;">
                    <i class="fas fa-eye me-1"></i>Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

@else
<div class="card border-0 rounded-4 shadow-sm mb-4 p-4 text-center">
    <i class="fas fa-tshirt fa-3x text-muted opacity-25 mb-3"></i>
    <h6 class="fw-bold">Tidak Ada Laundry Aktif</h6>
    <p class="text-muted small mb-3">Belum ada cucian yang sedang diproses saat ini.</p>
    <a href="{{ route('customer.booking') }}" class="btn btn-primary px-4">
        <i class="fas fa-calendar-plus me-2"></i>Booking Sekarang
    </a>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-receipt"></i></div>
                <div>
                    <div class="text-muted small">Total Transaksi</div>
                    <div class="fw-bold fs-4">{{ $transaksis->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-spinner"></i></div>
                <div>
                    <div class="text-muted small">Sedang Diproses</div>
                    <div class="fw-bold fs-4">{{ $transaksis->where('status', 'proses')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="text-muted small">Siap / Selesai</div>
                    <div class="fw-bold fs-4">{{ $transaksis->whereIn('status', ['selesai','diambil'])->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($bookings->isNotEmpty())
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3"><i class="fas fa-calendar-check me-2 text-primary"></i>Booking Terbaru</h6>
        @foreach ($bookings->take(3) as $booking)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <div>
                <div class="fw-semibold small">{{ $booking->layanan->nama_layanan ?? '-' }}</div>
                <div class="text-muted" style="font-size:.8rem;">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d F Y') }}</div>
            </div>
            @php $bc = ['pending'=>'warning','confirmed'=>'success','cancelled'=>'danger','completed'=>'secondary'] @endphp
            <span class="badge bg-{{ $bc[$booking->status] ?? 'secondary' }}">
                @switch($booking->status)
                    @case('pending') Menunggu @break
                    @case('confirmed') Dikonfirmasi @break
                    @case('cancelled') Dibatalkan @break
                    @default Selesai
                @endswitch
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif

@if ($transaksis->isNotEmpty())
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i>Riwayat Transaksi</h6>
            <a href="{{ route('customer.riwayat') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
        </div>
        @foreach ($transaksis->take(3) as $trx)
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <div>
                <div class="fw-semibold small">#{{ str_pad($trx->id_transaksi, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="text-muted" style="font-size:.8rem;">
                    {{ $trx->tanggal_masuk->format('d/m/Y') }} · {{ $trx->total_berat }} kg
                </div>
            </div>
            <div class="text-end">
                <div class="fw-bold text-primary small">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                @php $sc = ['proses'=>'warning','selesai'=>'success','diambil'=>'secondary'] @endphp
                <span class="badge bg-{{ $sc[$trx->status] ?? 'secondary' }}" style="font-size:.7rem;">
                    {{ ['proses'=>'Proses','selesai'=>'Siap Diambil','diambil'=>'Selesai'][$trx->status] ?? $trx->status }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
