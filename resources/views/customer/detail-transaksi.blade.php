@extends('layouts.customer')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')

@php
$statusDetailLabels = \App\Models\Transaksi::$statusDetailLabels;
$statusDetailIcons  = \App\Models\Transaksi::$statusDetailIcons;
$allSteps = array_keys($statusDetailLabels);
$currentIndex = array_search($transaksi->status_detail, $allSteps);
@endphp

<div class="mb-3">
    <a href="{{ route('customer.riwayat') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header p-4 rounded-top-4" style="background:linear-gradient(135deg,#1d4ed8,#06b6d4);color:white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-bold">Transaksi #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</h6>
                        <small style="opacity:.8;">Masuk: {{ $transaksi->tanggal_masuk->format('d F Y, H:i') }}</small>
                    </div>
                    <span class="badge bg-white text-primary fw-bold px-3">
                        {{ ['proses'=>'Diproses','selesai'=>'Siap Diambil','diambil'=>'Selesai'][$transaksi->status] ?? $transaksi->status }}
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-primary">Progres Laundry</h6>

                <div class="position-relative mb-4">
                    @foreach ($allSteps as $i => $step)
                    @php
                    $done    = $i < $currentIndex;
                    $current = $i === $currentIndex;
                    @endphp
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="d-flex flex-column align-items-center" style="min-width:36px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:36px;height:36px;
                                background:{{ $current ? '#2563eb' : ($done ? '#10b981' : '#e5e7eb') }};
                                color:{{ ($current || $done) ? 'white' : '#9ca3af' }};
                                font-size:.8rem;">
                                @if($done) <i class="fas fa-check"></i>
                                @else <i class="{{ $statusDetailIcons[$step] }}"></i>
                                @endif
                            </div>
                            @if (!$loop->last)
                            <div style="width:2px;height:28px;background:{{ $done ? '#10b981' : '#e5e7eb' }};margin-top:2px;"></div>
                            @endif
                        </div>
                        <div style="padding-top:6px;">
                            <div class="fw-semibold small" style="color:{{ $current ? '#2563eb' : ($done ? '#10b981' : '#9ca3af') }};">
                                {{ $statusDetailLabels[$step] }}
                                @if($current) <span class="badge bg-primary ms-1" style="font-size:.65rem;">Sekarang</span> @endif
                            </div>
                            @if ($current && $transaksi->catatan_status)
                            <div class="text-muted" style="font-size:.8rem;">{{ $transaksi->catatan_status }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-primary">Detail Layanan</h6>
                @foreach ($transaksi->detailTransaksi as $detail)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="small">{{ $detail->layanan->nama_layanan ?? '-' }}</span>
                    <span class="small text-muted">{{ $detail->berat }} kg × Rp {{ number_format($detail->layanan->harga_per_kg ?? 0, 0, ',', '.') }}</span>
                    <span class="fw-semibold small">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
                @if ($transaksi->pewangi)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="small"><i class="fas fa-spray-can me-1 text-muted"></i>Pewangi: {{ $transaksi->pewangi->nama_barang }}</span>
                    <span class="small text-muted">—</span>
                    <span class="small text-muted">Termasuk</span>
                </div>
                @endif
                <div class="d-flex justify-content-between py-3">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold text-primary">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                @if ($transaksi->booking && $transaksi->booking->biaya_antar_jemput)
                <div class="d-flex justify-content-between pb-2">
                    <span class="small text-muted">Biaya Antar/Jemput</span>
                    <span class="small">Rp {{ number_format($transaksi->booking->biaya_antar_jemput, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-primary">Info Transaksi</h6>
                <table class="table table-borderless table-sm small mb-0">
                    <tr>
                        <td class="text-muted ps-0">No. Transaksi</td>
                        <td class="fw-semibold">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Tanggal Masuk</td>
                        <td>{{ $transaksi->tanggal_masuk->format('d F Y') }}</td>
                    </tr>
                    @if ($transaksi->tanggal_selesai)
                    <tr>
                        <td class="text-muted ps-0">Tanggal Selesai</td>
                        <td>{{ $transaksi->tanggal_selesai->format('d F Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted ps-0">Total Berat</td>
                        <td>{{ $transaksi->total_berat }} kg</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Status</td>
                        <td>
                            <span class="badge bg-primary">{{ $statusDetailLabels[$transaksi->status_detail] ?? '-' }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if ($transaksi->booking)
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-primary">Info Booking</h6>
                <table class="table table-borderless table-sm small mb-0">
                    <tr>
                        <td class="text-muted ps-0">Tgl Booking</td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->booking->tanggal_booking)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Antar/Jemput</td>
                        <td>{{ ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'][$transaksi->booking->tipe_antar_jemput] ?? '-' }}</td>
                    </tr>
                    @if ($transaksi->booking->catatan)
                    <tr>
                        <td class="text-muted ps-0">Catatan</td>
                        <td>{{ $transaksi->booking->catatan }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif

        @if ($transaksi->pengembalian)
        <div class="card border-0 rounded-4 bg-success bg-opacity-10 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2 text-success"><i class="fas fa-check-circle me-2"></i>Pengembalian</h6>
                <p class="small text-muted mb-1">
                    Tanggal: {{ \Carbon\Carbon::parse($transaksi->pengembalian->tanggal_pengembalian)->format('d F Y, H:i') }}
                </p>
                <span class="badge bg-{{ $transaksi->pengembalian->status_pengembalian === 'sudah_diambil' ? 'success' : 'warning' }}">
                    {{ $transaksi->pengembalian->status_pengembalian === 'sudah_diambil' ? 'Sudah Diambil' : 'Siap Diambil' }}
                </span>
            </div>
        </div>
        @endif

        <div class="card border-0 rounded-4 shadow-sm mt-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-credit-card me-2"></i>Pembayaran</h6>
                @if ($transaksi->pembayaran)
                    @php
                        $p         = $transaksi->pembayaran;
                        $tagihan   = $transaksi->total_harga + ($transaksi->booking?->biaya_antar_jemput ?? 0);
                        $icons     = \App\Models\Pembayaran::$metodeIcons;
                        $labels    = \App\Models\Pembayaran::$metodeLabels;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Status</span>
                        @if ($p->status_bayar === 'lunas')
                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Lunas</span>
                        @else
                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Belum Lunas</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Metode</span>
                        <span class="small fw-semibold">
                            <i class="{{ $icons[$p->metode_bayar] ?? 'fas fa-money-bill' }} me-1 text-primary"></i>
                            {{ $labels[$p->metode_bayar] ?? $p->metode_bayar }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Total Dibayar</span>
                        <span class="fw-bold text-primary">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('customer.transaksi.faktur', $transaksi->id_transaksi) }}"
                       class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-file-invoice me-2"></i>Lihat Faktur Pembayaran
                    </a>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-clock fa-2x text-muted opacity-25 d-block mb-2"></i>
                        <p class="text-muted small mb-0">Pembayaran belum dicatat oleh admin.</p>
                        <p class="text-muted" style="font-size:.75rem;">Silakan hubungi laundry jika ada pertanyaan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
