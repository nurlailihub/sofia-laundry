@extends('layouts.admin')

@section('title', 'Edit Layanan')

@section('page-title', 'Edit Data Layanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.layanans.index') }}">Kelola Data Layanan</a></li>
    <li class="breadcrumb-item active">Edit Layanan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Kelola Data Layanan</h3>
            </div>
            <form action="{{ route('admin.layanans.update', $layanan->id_layanan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_layanan" class="form-control @error('nama_layanan') is-invalid @enderror"
                            value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required>
                        @error('nama_layanan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Harga per Kg <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="harga_per_kg" class="form-control @error('harga_per_kg') is-invalid @enderror"
                                value="{{ old('harga_per_kg', $layanan->harga_per_kg) }}" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text">/ Kg</span>
                            </div>
                            @error('harga_per_kg')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                            rows="3">{{ old('keterangan', $layanan->keterangan) }}</textarea>
                        @error('keterangan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.layanans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-info float-right">
                        <i class="fas fa-save mr-1"></i> Update Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
