@extends('layouts.admin')

@section('title', 'Profil & Pengaturan')
@section('page-title', 'Profil & Pengaturan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Profil & Pengaturan</li>
@endsection

@push('styles')
<style>
.profile-card { border-radius: 14px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); border: none; }
.profile-header {
    background: linear-gradient(135deg, #005F73 0%, #2BB1B1 100%);
    padding: 2rem 1.5rem; color: white; text-align: center;
}
.avatar-circle {
    width: 80px; height: 80px; border-radius: 50%;
    background: rgba(255,255,255,.2); border: 3px solid rgba(255,255,255,.5);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.2rem; color: white; margin: 0 auto 1rem;
}
.info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .55rem 0; border-bottom: 1px solid #f1f5f9; font-size: .875rem;
}
.info-row:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">

    {{-- ============ KOLOM KIRI ============ --}}
    <div class="col-lg-4 mb-4">
        <div class="card profile-card">
            <div class="profile-header">
                <div class="avatar-circle">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h5 class="mb-1 font-weight-bold">{{ $user->nama_user }}</h5>
                <div style="opacity:.8; font-size:.85rem;">{{ '@' }}{{ $user->username }}</div>
                <span class="badge badge-light mt-2" style="font-size:.8rem;">{{ strtoupper($user->role) }}</span>
            </div>
            <div class="card-body py-3 px-4">
                <div class="info-row">
                    <span class="text-muted">Nama</span>
                    <strong>{{ $user->nama_user }}</strong>
                </div>
                <div class="info-row">
                    <span class="text-muted">Username</span>
                    <strong>{{ $user->username }}</strong>
                </div>
                <div class="info-row">
                    <span class="text-muted">Role</span>
                    <strong>{{ ucfirst($user->role) }}</strong>
                </div>
                <div class="info-row">
                    <span class="text-muted">Login Terakhir</span>
                    <strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ KOLOM KANAN ============ --}}
    <div class="col-lg-8">
        <div class="card profile-card">
            <div class="card-body p-4">

                <p class="text-muted small text-uppercase font-weight-bold mb-4" style="letter-spacing:.5px;">
                    <i class="fas fa-user mr-1"></i>Edit Profil
                </p>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_user"
                                    class="form-control @error('nama_user') is-invalid @enderror"
                                    value="{{ old('nama_user', $user->nama_user) }}" required>
                                @error('nama_user')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}" required>
                                @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <p class="text-muted small text-uppercase font-weight-bold mb-3" style="letter-spacing:.5px;">
                        Ganti Password
                        <small class="text-muted font-weight-normal text-lowercase">(kosongkan jika tidak ingin ganti)</small>
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="newPass"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 6 karakter">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('newPass')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="confirmPass"
                                        class="form-control" placeholder="Ulangi password baru">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('confirmPass')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
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
