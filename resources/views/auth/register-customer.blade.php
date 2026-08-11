@extends('adminlte::master')

@section('classes_body', 'register-page')

@section('body')
<div class="register-box" style="width:440px;">
    <div class="register-logo">
        <a href="{{ route('admin.dashboard') }}"><b>Sofia</b> Laundry</a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Buat Akun untuk Pelanggan</p>
            <p class="text-muted text-center small">Akun ini akan digunakan pelanggan untuk login dan cek status laundry mereka.</p>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.register.customer.store') }}" method="POST">
                @csrf

                <div class="input-group mb-3">
                    <select name="id_pelanggan" class="form-control @error('id_pelanggan') is-invalid @enderror" required>
                        <option value="">— Pilih Pelanggan —</option>
                        @foreach ($pelanggans as $p)
                        <option value="{{ $p->id_pelanggan }}"
                            {{ (old('id_pelanggan') == $p->id_pelanggan || request('pelanggan') == $p->id_pelanggan) ? 'selected' : '' }}>
                            {{ $p->nama_pelanggan }} — {{ $p->no_hp }}
                        </option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-users"></span></div>
                    </div>
                    @error('id_pelanggan')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <small class="text-muted d-block mb-3">Hanya pelanggan yang belum punya akun yang ditampilkan.</small>

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                        placeholder="Username untuk login" value="{{ old('username') }}" required>
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

                <div class="row">
                    <div class="col-7">
                        <a href="{{ route('admin.pelanggans.index') }}" class="text-muted small">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Pelanggan
                        </a>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block">Buat Akun</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
