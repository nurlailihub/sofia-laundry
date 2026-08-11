@extends('layouts.customer')

@section('title', 'Cek Status Laundry')
@section('page-title', 'Cek Status Laundry')

@section('content')

@php
$statusDetailLabels = \App\Models\Transaksi::$statusDetailLabels;
$statusColors = ['proses'=>'warning','selesai'=>'success','diambil'=>'secondary'];
$statusLabels = ['proses'=>'Diproses','selesai'=>'Siap Diambil','diambil'=>'Sudah Diambil'];
@endphp

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4">
        @forelse ($transaksis as $trx)
        <div class="card border-0 bg-light rounded-3 mb-3 p-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="fw-bold">#{{ str_pad($trx->id_transaksi, 6, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-muted small ms-2">{{ $trx->tanggal_masuk->format('d F Y') }}</span>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $statusColors[$trx->status] ?? 'secondary' }}">
                        {{ $statusLabels[$trx->status] ?? $trx->status }}
                    </span>
                    <a href="{{ route('customer.transaksi.detail', $trx->id_transaksi) }}" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:.75rem;">
                        Detail
                    </a>
                </div>
            </div>
            <div class="row g-2 small text-muted">
                <div class="col-6 col-md-3">
                    <i class="fas fa-weight me-1"></i>{{ $trx->total_berat }} kg
                </div>
                <div class="col-6 col-md-3">
                    <i class="fas fa-money-bill me-1"></i>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                </div>
                <div class="col-12 col-md-6">
                    <i class="fas fa-circle me-1 text-primary"></i>
                    {{ $statusDetailLabels[$trx->status_detail] ?? '-' }}
                </div>
            </div>
            @if ($trx->catatan_status)
            <div class="mt-2 text-muted small">
                <i class="fas fa-comment me-1"></i>{{ $trx->catatan_status }}
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-3x opacity-25 mb-3"></i>
            <p>Belum ada riwayat transaksi.</p>
            <a href="{{ route('customer.booking') }}" class="btn btn-primary">Booking Layanan</a>
        </div>
        @endforelse

        <div class="mt-3">
            @if(method_exists($transaksis, 'links'))
                {{ $transaksis->links() }}
            @endif
        </div>
    </div>
</div>

@endsection
