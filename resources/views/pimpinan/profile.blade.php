@extends('layouts.pimpinan')

@section('title', 'Profil')
@section('page-title', 'Profil')

@section('breadcrumb')
<li class="breadcrumb-item active">Profil</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm" style="border-radius:12px;border:none;">
            <div class="card-header text-white" style="background:linear-gradient(135deg,#005F73,#2BB1B1);border-radius:12px 12px 0 0;">
                <div class="d-flex align-items-center gap-3 py-1">
                    <div style="width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.4rem;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold" style="font-size:1.1rem;">{{ auth()->user()->nama_user }}</div>
                        <small style="opacity:.75;">Pimpinan Laundry</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif

                <form action="{{ route('pimpinan.profile.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-weight-bold small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_user" class="form-control @error('nama_user') is-invalid @enderror"
                            value="{{ old('nama_user', auth()->user()->nama_user) }}" required>
                        @error('nama_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', auth()->user()->username) }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <hr>
                    <p class="small text-muted font-weight-bold text-uppercase mb-3" style="letter-spacing:.5px;">
                        Ganti Password <small class="text-muted font-weight-normal text-lowercase">(kosongkan jika tidak ingin ganti)</small>
                    </p>
                    <div class="form-group">
                        <label class="font-weight-bold small">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password" id="newPass" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 6 karakter">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePass('newPass')"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="confirmPass" class="form-control" placeholder="Ulangi password baru">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePass('confirmPass')"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save mr-1"></i>Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePass(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
