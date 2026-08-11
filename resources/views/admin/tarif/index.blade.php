@extends('layouts.admin')

@section('title', 'Tarif Antar / Jemput')
@section('page-title', 'Tarif Antar / Jemput')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Tarif Antar / Jemput</li>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-truck mr-2"></i>Kelola Tarif Default Antar / Jemput</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Tarif ini ditampilkan sebagai <strong>harga default</strong> di form booking pelanggan.
                    Admin tetap bisa mengubah tarif per-booking saat konfirmasi sesuai jarak actual.
                </div>

                @php
                    $icons = ['pickup' => 'fas fa-hand-holding', 'delivery' => 'fas fa-truck', 'both' => 'fas fa-exchange-alt'];
                    $colors = ['pickup' => 'info', 'delivery' => 'primary', 'both' => 'purple'];
                @endphp

                @foreach($tarifs as $tarif)
                <div class="card mb-3" style="border-left: 4px solid var(--{{ $colors[$tarif->tipe] ?? 'primary' }}, #17a2b8);">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="badge badge-{{ $colors[$tarif->tipe] ?? 'primary' }} mr-2">
                                    <i class="{{ $icons[$tarif->tipe] ?? 'fas fa-truck' }} mr-1"></i>
                                    {{ $tarif->label }}
                                </span>
                                <small class="text-muted">{{ $tarif->keterangan }}</small>
                            </div>
                            <span class="font-weight-bold text-success" style="font-size:1.1rem;">
                                Rp {{ number_format($tarif->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <form action="{{ route('admin.tarif.update', $tarif->id) }}" method="POST" class="d-flex align-items-end gap-2">
                            @csrf
                            @method('PUT')
                            <div class="flex-grow-1 mr-2">
                                <label class="small text-muted mb-1">Harga (Rp)</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="harga" class="form-control"
                                        value="{{ $tarif->harga }}" min="0" step="500" required>
                                </div>
                            </div>
                            <div class="flex-grow-1 mr-2">
                                <label class="small text-muted mb-1">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control form-control-sm"
                                    value="{{ $tarif->keterangan }}" placeholder="Opsional">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save mr-1"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
