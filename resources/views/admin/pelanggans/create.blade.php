@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')

@section('page-title', 'Tambah Pelanggan Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pelanggans.index') }}">Kelola Data Pelanggan</a></li>
    <li class="breadcrumb-item active">Tambah Pelanggan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-plus mr-1"></i> Form Tambah Kelola Data Pelanggan</h3>
            </div>
            <form action="{{ route('admin.pelanggans.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <h5 class="text-primary mb-3"><i class="fas fa-id-card mr-1"></i> Kelola Data Pelanggan</h5>

                    <div class="form-group">
                        <label>Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror"
                            value="{{ old('nama_pelanggan') }}" placeholder="Masukkan nama lengkap pelanggan" required>
                        @error('nama_pelanggan')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. HP / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                                @error('no_hp')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                    value="{{ old('alamat') }}" placeholder="Alamat lengkap pelanggan" required>
                                @error('alamat')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="buat_akun" name="buat_akun" value="1"
                                {{ old('buat_akun') ? 'checked' : '' }} onchange="toggleAkunSection()">
                            <label class="custom-control-label" for="buat_akun">
                                <i class="fas fa-user-tag text-primary"></i> <strong>Buat Akun Pelanggan Sekaligus</strong>
                            </label>
                        </div>
                        <small class="text-muted">Centang untuk membuat akun login agar pelanggan bisa login ke sistem.</small>
                    </div>

                    <div id="akunSection" style="{{ old('buat_akun') ? '' : 'display:none;' }}">
                        <h5 class="text-success mb-3"><i class="fas fa-key mr-1"></i> Data Akun Login</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}" placeholder="Username untuk login">
                                    @error('username')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 6 karakter">
                                    @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.pelanggans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="fas fa-save mr-1"></i> Simpan Pelanggan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAkunSection() {
    var checkbox = document.getElementById('buat_akun');
    var section = document.getElementById('akunSection');
    if (checkbox.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
}
</script>
@endpush
