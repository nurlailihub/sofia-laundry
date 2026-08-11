@extends('layouts.admin')

@section('title', 'Edit Pengambilan')

@section('page-title', 'Edit Pengambilan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pengembalians.index') }}">Kelola Pengambilan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Kelola Pengambilan</h3>
            </div>
            <form action="{{ route('admin.pengembalians.update', $pengembalian->id_pengembalian) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pelanggan</label>
                                <input type="text" class="form-control"
                                    value="{{ $pengembalian->transaksi->pelanggan->nama_pelanggan ?? '-' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pengambilan</label>
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('d/m/Y H:i') }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status_pengembalian" class="form-control @error('status_pengembalian') is-invalid @enderror" required>
                            <option value="siap_diambil" {{ old('status_pengembalian', $pengembalian->status_pengembalian) == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                            <option value="sudah_diambil" {{ old('status_pengembalian', $pengembalian->status_pengembalian) == 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                        </select>
                        @error('status_pengembalian')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $pengembalian->catatan) }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.pengembalians.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-info float-right">
                        <i class="fas fa-save mr-1"></i> Update Pengambilan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
