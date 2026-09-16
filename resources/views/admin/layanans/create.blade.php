@extends('layouts.admin')

@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.layanans.index') }}">Kelola Data Layanan</a></li>
    <li class="breadcrumb-item active">Tambah Layanan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-concierge-bell mr-1"></i> Form Tambah Layanan</h3>
            </div>
            <form action="{{ route('admin.layanans.store') }}" method="POST">
                @csrf
                <div class="card-body">

                    {{-- Nama Layanan --}}
                    <div class="form-group">
                        <label>Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_layanan"
                            class="form-control @error('nama_layanan') is-invalid @enderror"
                            value="{{ old('nama_layanan') }}"
                            placeholder="Contoh: Cuci Setrika, Sprei, Selimut" required>
                        @error('nama_layanan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tipe Harga --}}
                    <div class="form-group">
                        <label>Tipe Harga <span class="text-danger">*</span></label>
                        <div class="d-flex" style="gap: 1rem;">
                            <div class="custom-control custom-radio">
                                <input type="radio" id="tipe_kg" name="tipe_harga" value="kg"
                                    class="custom-control-input"
                                    {{ old('tipe_harga', 'kg') === 'kg' ? 'checked' : '' }}
                                    onchange="updateSatuanLabel()">
                                <label class="custom-control-label" for="tipe_kg">
                                    <i class="fas fa-weight-hanging text-primary mr-1"></i>
                                    Per Kilogram (kg)
                                    <small class="text-muted d-block">Untuk layanan cuci reguler</small>
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="tipe_satuan" name="tipe_harga" value="satuan"
                                    class="custom-control-input"
                                    {{ old('tipe_harga') === 'satuan' ? 'checked' : '' }}
                                    onchange="updateSatuanLabel()">
                                <label class="custom-control-label" for="tipe_satuan">
                                    <i class="fas fa-th-large text-success mr-1"></i>
                                    Per Satuan (pcs)
                                    <small class="text-muted d-block">Untuk sprei, selimut, dll.</small>
                                </label>
                            </div>
                        </div>
                        @error('tipe_harga')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="form-group">
                        <label>Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="harga_per_kg"
                                class="form-control @error('harga_per_kg') is-invalid @enderror"
                                value="{{ old('harga_per_kg') }}"
                                placeholder="5000" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuanLabel">/ kg</span>
                            </div>
                            @error('harga_per_kg')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <small class="text-muted" id="satuanHint">Harga yang dikenakan per kilogram cucian.</small>
                    </div>

                    {{-- Keterangan --}}
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                            rows="3" placeholder="Deskripsi layanan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.layanans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="fas fa-save mr-1"></i> Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateSatuanLabel() {
    var isSatuan = document.getElementById('tipe_satuan').checked;
    document.getElementById('satuanLabel').textContent = isSatuan ? '/ pcs' : '/ kg';
    document.getElementById('satuanHint').textContent  = isSatuan
        ? 'Harga yang dikenakan per pcs/item (contoh: 1 sprei = Rp 15.000).'
        : 'Harga yang dikenakan per kilogram cucian.';
}
// Inisialisasi saat load (untuk kasus old() value)
updateSatuanLabel();
</script>
@endpush
