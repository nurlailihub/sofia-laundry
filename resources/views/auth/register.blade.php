@extends('adminlte::master')

@section('classes_body', 'register-page')

@section('body')
<div class="register-box">
    <div class="register-logo">
        <a href="{{ route('admin.dashboard') }}"><b>Sofia</b> Laundry</a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Daftarkan User Baru</p>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.register.store') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="nama_user" class="form-control @error('nama_user') is-invalid @enderror"
                        placeholder="Nama Lengkap" value="{{ old('nama_user') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                    @error('nama_user')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        placeholder="Username" value="{{ old('username') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-at"></span></div>
                    </div>
                    @error('username')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password (min. 6 karakter)" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Konfirmasi Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">— Pilih Role —</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pimpinan" {{ old('role') === 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user-tag"></span></div>
                    </div>
                    @error('role')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-8">
                        <a href="{{ route('admin.users.index') }}" class="text-muted">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar User
                        </a>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
