@extends('layouts.admin')

@section('title', 'Edit Transaksi')

@section('page-title', 'Edit Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.transaksis.index') }}">Buat Transaksi Laundry</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Form Edit Buat Transaksi Laundry</h3>
            </div>
            <form action="{{ route('admin.transaksis.update', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pelanggan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. HP</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ $transaksi->pelanggan->no_hp ?? '-' }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d/m/Y') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estimasi Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                    value="{{ old('tanggal_selesai', $transaksi->tanggal_selesai ? \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('Y-m-d') : '') }}">
                                @error('tanggal_selesai')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="proses" {{ old('status', $transaksi->status) == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ old('status', $transaksi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="diambil" {{ old('status', $transaksi->status) == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pewangi</label>
                                <select name="id_pewangi" class="form-control @error('id_pewangi') is-invalid @enderror">
                                    <option value="">Tidak Pakai Pewangi</option>
                                    @foreach($stok_barangs as $barang)
                                        <option value="{{ $barang->id_barang }}" {{ old('id_pewangi', $transaksi->id_pewangi) == $barang->id_barang ? 'selected' : '' }}>
                                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_pewangi')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3"><i class="fas fa-list-alt mr-1"></i> Detail Layanan</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th>Berat (Kg)</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->detailTransaksi as $detail)
                            <tr>
                                <td>{{ $detail->layanan->nama_layanan ?? '-' }}</td>
                                <td>{{ number_format($detail->berat, 2) }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <th colspan="1">Total</th>
                                <th>{{ number_format($transaksi->total_berat, 2) }} Kg</th>
                                <th>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>

                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="kirim_wa" name="kirim_wa" value="1" checked>
                                <label class="custom-control-label font-weight-bold" for="kirim_wa">
                                    <i class="fab fa-whatsapp text-success me-1"></i>Kirim Notifikasi WhatsApp ke Pelanggan
                                </label>
                                <small class="text-muted d-block ml-4">Pelanggan akan mendapat notifikasi perubahan status laundry melalui WhatsApp</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.transaksis.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-info float-right">
                        <i class="fas fa-save mr-1"></i> Update Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
