@extends('layouts.admin')

@section('title', 'Tambah Pengambilan')

@section('page-title', 'Tambah Pengambilan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pengembalians.index') }}">Kelola Pengambilan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .transaksi-option { padding: 4px 0; }
    .transaksi-option .nama { font-weight: 600; }
    .transaksi-option .info { font-size: .8rem; color: #6b7280; }
    .transaksi-option .tagihan { font-weight: 700; color: #1d4ed8; }
    .transaksi-option .status-lunas { color: #059669; font-weight: 600; }
    .transaksi-option .status-belum { color: #dc2626; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-hand-holding mr-1"></i> Form Tambah Kelola Pengambilan</h3>
            </div>
            <form action="{{ route('admin.pengembalians.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Transaksi <span class="text-danger">*</span></label>
                        <select name="id_transaksi" id="transaksi_select" class="form-select select2 @error('id_transaksi') is-invalid @enderror" required>
                            <option value="">Pilih Transaksi</option>
                            @foreach($transaksis as $transaksi)
                                @php
                                    $totalTagihan = $transaksi->total_harga + ($transaksi->booking?->biaya_antar_jemput ?? 0);
                                    $sudahBayar = $transaksi->pembayaran && $transaksi->pembayaran->status_bayar === 'lunas';
                                    $metodeLabel = ['cash'=>'Cash','transfer'=>'Transfer','qris'=>'QRIS'];
                                @endphp
                                <option value="{{ $transaksi->id_transaksi }}"
                                    data-id-booking="{{ $transaksi->id_booking ?? '' }}"
                                    data-kode-reservasi="{{ $transaksi->booking?->kode_reservasi ?? '-' }}"
                                    data-tagihan="{{ $totalTagihan }}"
                                    data-sudah-bayar="{{ $sudahBayar ? '1' : '0' }}"
                                    {{ old('id_transaksi') == $transaksi->id_transaksi ? 'selected' : '' }}>
                                    {{ $transaksi->pelanggan->nama_pelanggan }} —
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') }} —
                                    {{ number_format($transaksi->total_berat, 2) }} Kg —
                                    Rp {{ number_format($totalTagihan, 0, ',', '.') }} —
                                    {{ $sudahBayar ? 'LUNAS' : 'BELUM BAYAR' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya transaksi dengan status "Selesai" yang belum diambil</small>
                        @error('id_transaksi')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="infoTagihan" class="mb-3 p-3 rounded" style="background:#f8fafc;border:1px solid #e5e7eb;display:none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="small text-muted mb-1">Tagihan</div>
                                <div class="fw-bold text-primary" style="font-size:1.2rem;" id="tagihanAmount">Rp 0</div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="small text-muted mb-1">Status Pembayaran</div>
                                <div id="statusBayarBadge"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>ID Reservasi / Booking</label>
                        <input type="text" id="booking_display" class="form-control" readonly
                            placeholder="Otomatis terisi dari transaksi">
                        <input type="hidden" name="id_booking" id="booking_input">
                        <small class="text-muted">Otomatis terisi berdasarkan transaksi yang dipilih</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pengambilan <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal_pengembalian" class="form-control @error('tanggal_pengembalian') is-invalid @enderror"
                                    value="{{ old('tanggal_pengembalian', date('Y-m-d\TH:i')) }}" required>
                                @error('tanggal_pengembalian')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status_pengembalian" class="form-control @error('status_pengembalian') is-invalid @enderror" required>
                                    <option value="siap_diambil" {{ old('status_pengembalian') == 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                                    <option value="sudah_diambil" {{ old('status_pengembalian') == 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                                </select>
                                @error('status_pengembalian')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="kirim_notifikasi" name="kirim_notifikasi" value="1"
                                {{ old('kirim_notifikasi', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="kirim_notifikasi">
                                <i class="fab fa-whatsapp text-success"></i> Kirim Notifikasi WhatsApp ke Pelanggan
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.pengembalians.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary float-right" id="btnSubmit">
                        <i class="fas fa-save mr-1"></i> Simpan Pengambilan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: null,
    });

    $('#transaksi_select').on('change', function() {
        var selected = $(this).find(':selected');
        var idBooking = selected.data('id-booking');
        var kodeReservasi = selected.data('kode-reservasi');
        var tagihan = selected.data('tagihan') || 0;
        var sudahBayar = selected.data('sudah-bayar') === 1;

        if (idBooking) {
            $('#booking_input').val(idBooking);
            $('#booking_display').val(kodeReservasi);
        } else {
            $('#booking_input').val('');
            $('#booking_display').val('');
        }

        if (tagihan > 0) {
            $('#infoTagihan').show();
            $('#tagihanAmount').text('Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(tagihan)));
            if (sudahBayar) {
                $('#statusBayarBadge').html('<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Lunas</span>');
                $('#btnSubmit').prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
            } else {
                $('#statusBayarBadge').html('<span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Belum Bayar</span>');
                $('#btnSubmit').prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
                Swal.fire({
                    icon: 'warning',
                    title: 'Pembayaran Belum Lunas',
                    text: 'Tagihan harus lunas sebelum pengambilan bisa dicatat.',
                    confirmButtonText: 'OK'
                });
            }
        } else {
            $('#infoTagihan').hide();
        }
    });

    $('#transaksi_select').trigger('change');
});
</script>
@endpush
