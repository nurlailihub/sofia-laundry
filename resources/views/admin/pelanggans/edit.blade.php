@extends('layouts.admin')

@section('title', 'Edit Pelanggan')

@section('page-title', 'Edit Data Pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pelanggans.index') }}">Kelola Data Pelanggan</a></li>
    <li class="breadcrumb-item active">Edit Pelanggan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Form Edit Kelola Data Pelanggan</h3>
            </div>
            <form action="{{ route('admin.pelanggans.update', $pelanggan->id_pelanggan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID Pelanggan</label>
                                <input type="text" class="form-control" value="#{{ $pelanggan->id_pelanggan }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Akun</label>
                                @if($pelanggan->user)
                                    <input type="text" class="form-control" value="Punya Akun ({{ $pelanggan->user->username }})" disabled>
                                @else
                                    <input type="text" class="form-control" value="Belum Punya Akun" disabled>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror"
                            value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
                        @error('nama_pelanggan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. HP / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp', $pelanggan->no_hp) }}" required>
                                @error('no_hp')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat', $pelanggan->alamat) }}" required>
                                @error('alamat')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.pelanggans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-info float-right">
                        <i class="fas fa-save mr-1"></i> Update Pelanggan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
