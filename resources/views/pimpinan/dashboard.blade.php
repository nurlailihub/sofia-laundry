@extends('layouts.pimpinan')

@section('title', 'Dashboard Pimpinan')
@section('page-title', 'Dashboard Pimpinan')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
.stat-card { border-radius: 12px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,.07); transition: transform .2s; }
.stat-card:hover { transform: translateY(-2px); }
.stat-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
.stat-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; color: #6b7280; margin-bottom: .2rem; }
.stat-value { font-size: 1.5rem; font-weight: 800; color: #1a202c; line-height: 1.2; }
.stat-sub { font-size: .8rem; color: #6b7280; margin-top: .15rem; }
.section-title { font-size: .7rem; text-transform: uppercase; letter-spacing: .7px; color: #94a3b8; font-weight: 700; margin-bottom: 1rem; }
</style>
@endpush

@section('content')

{{-- Selamat Datang --}}
<div class="p-4 mb-4 rounded-lg" style="background:linear-gradient(135deg,#005F73,#2BB1B1);color:#fff;border-radius:12px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1 font-weight-bold" style="font-family:Montserrat,sans-serif;">
                Selamat datang, {{ auth()->user()->nama_user }} 👋
            </h4>
            <p class="mb-0" style="opacity:.8;font-size:.9rem;">
                Berikut ringkasan performa Sofia Laundry — {{ now()->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
        <div style="opacity:.3;font-size:4rem;">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
</div>

{{-- Statistik Utama --}}
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon mr-3" style="background:#eff6ff;">
                    <i class="fas fa-money-bill-wave" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div class="stat-label">Pendapatan Hari Ini</div>
                    <div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon mr-3" style="background:#f0fdf4;">
                    <i class="fas fa-calendar-check" style="color:#059669;"></i>
                </div>
                <div>
                    <div class="stat-label">Pendapatan Bulan Ini</div>
                    <div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
                    <div class="stat-sub">Terbayar: Rp {{ number_format($pendapatanTerbayarBulanIni, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon mr-3" style="background:#fef3c7;">
                    <i class="fas fa-shopping-cart" style="color:#d97706;"></i>
                </div>
                <div>
                    <div class="stat-label">Transaksi Bulan Ini</div>
                    <div class="stat-value">{{ $totalTransaksiBulanIni }}</div>
                    <div class="stat-sub">Total order masuk</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="stat-icon mr-3" style="background:#fdf2f8;">
                    <i class="fas fa-users" style="color:#9333ea;"></i>
                </div>
                <div>
                    <div class="stat-label">Total Pelanggan</div>
                    <div class="stat-value">{{ $totalPelanggan }}</div>
                    <div class="stat-sub">+{{ $pelangganBaru }} baru (30 hari)</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Info Operasional --}}
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card stat-card p-3" style="border-left:4px solid #f59e0b;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Booking Menunggu Konfirmasi</div>
                    <div class="stat-value text-warning">{{ $bookingPending }}</div>
                </div>
                <i class="fas fa-clock" style="font-size:2rem;color:#f59e0b;opacity:.3;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card stat-card p-3" style="border-left:4px solid #ef4444;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Transaksi Belum Lunas</div>
                    <div class="stat-value text-danger">{{ $transaksiPiutang }}</div>
                </div>
                <i class="fas fa-exclamation-circle" style="font-size:2rem;color:#ef4444;opacity:.3;"></i>
            </div>
        </div>
    </div>
</div>

{{-- Grafik Transaksi Per Bulan --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm" style="border-radius:12px;">
            <div class="card-header" style="background:#fff;border-bottom:1px solid #f1f5f9;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="section-title mb-0">Grafik Transaksi & Pendapatan</div>
                        <h5 class="mb-0 font-weight-bold" style="color:#1a202c;">Tahun {{ $tahunIni }}</h5>
                    </div>
                    <a href="{{ route('admin.laporan.pertahun.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="chartTransaksi" style="max-height:300px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Transaksi Terbaru --}}
<div class="card shadow-sm" style="border-radius:12px;">
    <div class="card-header" style="background:#fff;border-bottom:1px solid #f1f5f9;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="section-title mb-0">Transaksi Terbaru</div>
                <h5 class="mb-0 font-weight-bold" style="color:#1a202c;">8 Transaksi Terakhir</h5>
            </div>
            <a href="{{ route('admin.laporan.transaksi.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-list mr-1"></i>Semua Laporan
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.875rem;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="pl-4">No. Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Tgl Masuk</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $trx)
                    <tr>
                        <td class="pl-4">
                            <span class="badge badge-primary">#{{ str_pad($trx->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="font-weight-bold">{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->tanggal_masuk)->format('d/m/Y') }}</td>
                        <td class="text-right font-weight-bold">Rp {{ number_format($trx->total_tagihan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @switch($trx->status)
                                @case('proses') <span class="badge badge-warning">Proses</span> @break
                                @case('selesai') <span class="badge badge-success">Selesai</span> @break
                                @default <span class="badge badge-info">Diambil</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            @if($trx->pembayaran)
                                @if($trx->pembayaran->status_bayar === 'lunas')
                                <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Lunas</span>
                                @else
                                <span class="badge badge-warning">Belum</span>
                                @endif
                            @else
                                <span class="badge badge-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const labels   = {!! $labelsChart !!};
const dataTrx  = {!! $dataTransaksi !!};
const dataPend = {!! $dataPendapatan !!};

const ctx = document.getElementById('chartTransaksi').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Jumlah Transaksi',
                data: dataTrx,
                backgroundColor: 'rgba(37,99,235,0.15)',
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 6,
                type: 'bar',
                yAxisID: 'y',
            },
            {
                label: 'Total Pendapatan (Rp)',
                data: dataPend,
                borderColor: '#059669',
                backgroundColor: 'rgba(5,150,105,0.1)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                type: 'line',
                yAxisID: 'y1',
                pointRadius: 4,
                pointBackgroundColor: '#059669',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        if (ctx.datasetIndex === 1) {
                            return ' Rp ' + Math.round(ctx.raw).toLocaleString('id-ID');
                        }
                        return ' ' + ctx.raw + ' transaksi';
                    }
                }
            }
        },
        scales: {
            y: {
                position: 'left',
                title: { display: true, text: 'Jumlah Transaksi' },
                ticks: { stepSize: 1 },
                grid: { color: 'rgba(0,0,0,.04)' },
            },
            y1: {
                position: 'right',
                title: { display: true, text: 'Pendapatan (Rp)' },
                grid: { drawOnChartArea: false },
                ticks: {
                    callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K'
                }
            }
        }
    }
});
</script>
@endpush
