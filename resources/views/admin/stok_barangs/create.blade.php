@extends('layouts.admin')

@section('title', 'Tambah Stok Barang')

@section('page-title', 'Tambah Stok Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.stok_barangs.index') }}">Kelola Stok Barang</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-box mr-1"></i> Form Tambah Kelola Stok Barang</h3>
            </div>
            <form action="{{ route('admin.stok_barangs.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                            value="{{ old('nama_barang') }}" placeholder="Contoh: Detergen, Pewangi" required>
                        @error('nama_barang')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-control @error('satuan') is-invalid @enderror" required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="botol" {{ old('satuan') == 'botol' ? 'selected' : '' }}>Botol</option>
                                </select>
                                @error('satuan')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                    value="{{ old('stok', 0) }}" min="0" required>
                                @error('stok')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Minimum Stok <span class="text-danger">*</span></label>
                        <input type="number" name="minimum_stok" class="form-control @error('minimum_stok') is-invalid @enderror"
                            value="{{ old('minimum_stok', 0) }}" min="0" required>
                        <small class="text-muted">Jika stok di bawah angka ini, akan muncul peringatan stok rendah.</small>
                        @error('minimum_stok')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.stok_barangs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="fas fa-save mr-1"></i> Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
