@extends('layouts.admin')

@section('title', 'Konfirmasi Booking')
@section('page-title', 'Konfirmasi Booking & Input Berat Asli')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Kelola Booking</a></li>
<li class="breadcrumb-item active">Konfirmasi</li>
@endsection

@push('styles')
<style>
.info-box-booking {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 1.25rem;
}
.detail-row-item {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: .75rem;
    position: relative;
}
.detail-row-item:last-child { margin-bottom: 0; }
.subtotal-display {
    font-size: 1rem;
    font-weight: 700;
    color: #2563eb;
}
.berat-asli-input {
    border: 2px solid #2563eb;
    border-radius: 6px;
    font-weight: 600;
}
</style>
@endpush

@section('content')

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">
    {{-- Kolom Kiri: Data Booking Pelanggan --}}
    <div class="col-lg-5">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-list mr-2"></i>Data Booking Pelanggan</h3>
            </div>
            <div class="card-body">

                {{-- Kode Reservasi & Status --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;">No. Reservasi</div>
                        <div style="font-size:1.2rem;font-weight:800;color:#1e3a8a;">
                            {{ $booking->kode_reservasi ?? '#' . str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                    <span class="badge badge-warning badge-lg" style="font-size:.85rem;padding:.5rem 1rem;">
                        <i class="fas fa-clock mr-1"></i>Pending
                    </span>
                </div>

                <hr>

                {{-- Info Pelanggan --}}
                <div class="info-box-booking mb-3">
                    <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                        <i class="fas fa-user mr-1"></i>Data Pelanggan
                    </div>
                    <div class="font-weight-bold">{{ $booking->pelanggan->nama_pelanggan ?? '-' }}</div>
                    <div class="text-muted small">{{ $booking->pelanggan->no_hp ?? '-' }}</div>
                    <div class="text-muted small">{{ $booking->pelanggan->alamat ?? '-' }}</div>
                </div>

                {{-- Info Booking --}}
                <div class="info-box-booking mb-3">
                    <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                        <i class="fas fa-calendar mr-1"></i>Detail Booking
                    </div>
                    <table class="table table-sm table-borderless mb-0" style="font-size:.875rem;">
                        <tr>
                            <td class="text-muted pl-0" width="45%">Tanggal Booking</td>
                            <td class="font-weight-bold">
                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}
                                @if($booking->waktu_booking) — {{ $booking->waktu_booking }} @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted pl-0">Estimasi Berat</td>
                            <td class="font-weight-bold text-warning">{{ $booking->estimasi_berat ?? 0 }} kg</td>
                        </tr>
                        @php $tipeLabel = ['none'=>'Antar Sendiri','pickup'=>'Dijemput Admin','delivery'=>'Diantar Admin','both'=>'Jemput & Antar']; @endphp
                        <tr>
                            <td class="text-muted pl-0">Antar/Jemput</td>
                            <td>{{ $tipeLabel[$booking->tipe_antar_jemput] ?? '-' }}</td>
                        </tr>
                        @if($booking->tipe_antar_jemput !== 'none' && $booking->biaya_antar_jemput > 0)
                        <tr>
                            <td class="text-muted pl-0">Biaya Antar/Jemput</td>
                            <td>Rp {{ number_format($booking->biaya_antar_jemput, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($booking->alamat_jemput)
                        <tr>
                            <td class="text-muted pl-0">Alamat Jemput</td>
                            <td>{{ $booking->alamat_jemput }}</td>
                        </tr>
                        @endif
                        @if($booking->alamat_antar)
                        <tr>
                            <td class="text-muted pl-0">Alamat Antar</td>
                            <td>{{ $booking->alamat_antar }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                {{-- Layanan yang Dipesan --}}
                @php
                    $bookingLayanans = $booking->layanans->isNotEmpty()
                        ? $booking->layanans
                        : collect([['layanan' => $booking->layanan, 'estimasi_berat' => $booking->estimasi_berat, 'estimasi_subtotal' => ($booking->estimasi_berat ?? 0) * ($booking->layanan->harga_per_kg ?? 0)]]);
                @endphp
                <div class="info-box-booking mb-3" style="border-color:#2563eb;background:#eff6ff;">
                    <div class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                        <i class="fas fa-concierge-bell mr-1 text-primary"></i>Layanan yang Dipesan
                        <span class="badge badge-primary ml-1">{{ $bookingLayanans->count() }}</span>
                    </div>
                    @foreach($bookingLayanans as $bl)
                    @php
                        $namaLayanan  = is_array($bl) ? ($bl['layanan']->nama_layanan ?? '-') : ($bl->layanan->nama_layanan ?? '-');
                        $hargaPerKg   = is_array($bl) ? ($bl['layanan']->harga_per_kg ?? 0) : ($bl->layanan->harga_per_kg ?? 0);
                        $estimasiBerat= is_array($bl) ? ($bl['estimasi_berat'] ?? 0) : ($bl->estimasi_berat ?? 0);
                        $subtotal     = is_array($bl) ? ($bl['estimasi_subtotal'] ?? 0) : ($bl->estimasi_subtotal ?? 0);
                    @endphp
                    <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <div class="font-weight-bold small">{{ $namaLayanan }}</div>
                            <div class="text-muted" style="font-size:.75rem;">
                                Rp {{ number_format($hargaPerKg, 0, ',', '.') }}/kg
                                @if($estimasiBerat > 0) · est. {{ $estimasiBerat }} kg @endif
                            </div>
                        </div>
                        <div class="text-right">
                            @if($subtotal > 0)
                            <span class="badge badge-light text-primary font-weight-bold">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Informasi DP --}}
                @if($booking->dp_bayar > 0)
                <div class="info-box-booking" style="border-color:#10b981;background:#f0fdf4;">
                    <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                        <i class="fas fa-money-bill-wave mr-1 text-success"></i>Status Pembayaran DP
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small">DP Dibayar</div>
                            <div class="font-weight-bold text-success">Rp {{ number_format($booking->dp_bayar, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-muted small">Metode</div>
                            <div class="font-weight-bold">{{ strtoupper($booking->metode_bayar ?? '-') }}</div>
                        </div>
                    </div>
                </div>
                @else
                <div class="info-box-booking" style="border-color:#f59e0b;background:#fffbeb;">
                    <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                    <small class="text-muted">Pelanggan belum membayar DP</small>
                </div>
                @endif

                @if($booking->catatan)
                <div class="mt-3 p-2 rounded" style="background:#fef3c7;border:1px solid #fde68a;font-size:.85rem;">
                    <i class="fas fa-sticky-note text-warning mr-1"></i>
                    <strong>Catatan Pelanggan:</strong> {{ $booking->catatan }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Form Konfirmasi & Input Berat Asli --}}
    <div class="col-lg-7">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-balance-scale mr-2"></i>Form Konfirmasi — Input Berat & Layanan Asli
                </h3>
            </div>
            <form action="{{ route('admin.bookings.confirm', $booking->id_booking) }}" method="POST" id="formKonfirmasi">
                @csrf
                <div class="card-body">

                    {{-- Peringatan Cocokkan Berat --}}
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Timbang cucian pelanggan dan input berat <strong>asli</strong> di bawah. Data ini akan langsung masuk ke transaksi.
                    </div>

                    {{-- Perbandingan Estimasi vs Asli --}}
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background:#fef3c7;border:2px dashed #f59e0b;">
                                <div class="text-muted small mb-1">Estimasi Pelanggan</div>
                                <div style="font-size:1.75rem;font-weight:800;color:#d97706;">
                                    {{ $booking->estimasi_berat ?? 0 }} kg
                                </div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    @if($booking->layanans->isNotEmpty())
                                        {{ $booking->layanans->map(fn($bl) => $bl->layanan->nama_layanan ?? '')->filter()->join(', ') }}
                                    @else
                                        {{ $booking->layanan->nama_layanan ?? '-' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded text-center" style="background:#dbeafe;border:2px dashed #2563eb;">
                                <div class="text-muted small mb-1">Berat Asli (Ditimbang)</div>
                                <div style="font-size:1.75rem;font-weight:800;color:#2563eb;" id="totalBeratDisplay">
                                    0.00 kg
                                </div>
                                <div class="text-muted" style="font-size:.75rem;">dari input di bawah</div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Layanan & Berat Asli --}}
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-list mr-1"></i>Detail Layanan & Berat Asli
                            <span class="text-danger">*</span>
                        </label>
                        <small class="text-muted d-block mb-2">
                            Cocokkan dengan cucian yang dibawa. Berat asli menentukan harga final.
                        </small>

                        <div id="detailContainer">
                            @php
                                $prefillRows = $booking->layanans->isNotEmpty()
                                    ? $booking->layanans
                                    : collect([(object)['id_layanan' => $booking->id_layanan, 'estimasi_berat' => $booking->estimasi_berat]]);
                            @endphp
                            @foreach($prefillRows as $i => $bl)
                            @php
                                $prefillLayananId = is_object($bl) && isset($bl->id_layanan) ? $bl->id_layanan : $booking->id_layanan;
                                $prefillBerat     = is_object($bl) && isset($bl->estimasi_berat) ? $bl->estimasi_berat : $booking->estimasi_berat;
                            @endphp
                            <div class="detail-row-item" id="row-{{ $i }}" data-row-id="{{ $i }}">
                                <div class="row align-items-end">
                                    <div class="col-md-5">
                                        <label class="small text-muted">Jenis Layanan <span class="text-danger">*</span></label>
                                        <select name="details[{{ $i }}][id_layanan]" class="form-control form-control-sm layanan-select" required data-row="{{ $i }}">
                                            <option value="">— Pilih Layanan —</option>
                                            @foreach($layanans as $l)
                                            <option value="{{ $l->id_layanan }}"
                                                data-harga="{{ $l->harga_per_kg }}"
                                                {{ $prefillLayananId == $l->id_layanan ? 'selected' : '' }}>
                                                {{ $l->nama_layanan }} — Rp {{ number_format($l->harga_per_kg, 0, ',', '.') }}/kg
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">Berat Asli (kg) <span class="text-danger">*</span></label>
                                        <input type="number" name="details[{{ $i }}][berat]"
                                            class="form-control form-control-sm berat-asli-input berat-input"
                                            step="0.1" min="0.1" placeholder="0.0" required data-row="{{ $i }}"
                                            value="{{ old('details.' . $i . '.berat', $prefillBerat) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">Subtotal</label>
                                        <div class="subtotal-display" id="subtotal-{{ $i }}">Rp 0</div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row"
                                            style="{{ $loop->count > 1 ? '' : 'display:none;' }}" data-row="{{ $i }}" title="Hapus baris">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" id="btnTambahLayanan" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-plus mr-1"></i>Tambah Layanan Lain
                        </button>
                    </div>

                    {{-- Rekap Total --}}
                    <div class="p-3 rounded mb-3" style="background:#f0fdf4;border:1px solid #6ee7b7;" id="rekapTotal">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Total Berat Asli</span>
                            <strong id="rekapBerat">0.00 kg</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Total Layanan</span>
                            <strong id="rekapHargaLayanan">Rp 0</strong>
                        </div>
                        @if($booking->tipe_antar_jemput !== 'none')
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small"><i class="fas fa-truck mr-1"></i>Biaya Antar/Jemput</span>
                            <strong id="rekapBiayaAntar">Rp 0</strong>
                        </div>
                        @endif
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold">TOTAL TAGIHAN</span>
                            <span style="font-size:1.2rem;font-weight:800;color:#059669;" id="rekapTotal_">Rp 0</span>
                        </div>
                        @if(($booking->dp_bayar ?? 0) > 0)
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted small">DP Sudah Dibayar</span>
                            <span class="text-success font-weight-bold">- Rp {{ number_format($booking->dp_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Sisa yang Harus Dibayar</span>
                            <span class="text-danger font-weight-bold" id="rekapSisa">Rp 0</span>
                        </div>
                        @endif
                    </div>

                    {{-- Input Biaya Antar/Jemput (hanya jika ada tipe antar) --}}
                    @if($booking->tipe_antar_jemput !== 'none')
                    @php
                        $tipeLabel   = ['pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'];
                        $tarifDB     = $tarifDefault[$booking->tipe_antar_jemput] ?? null;
                        $hargaDefault = $tarifDB ? (float)$tarifDB->harga : 0;
                        $prefillBiaya = $booking->biaya_antar_jemput > 0
                            ? $booking->biaya_antar_jemput
                            : $hargaDefault;
                    @endphp
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-truck mr-1 text-info"></i>Biaya Antar/Jemput
                            <span class="badge badge-info ml-1" style="font-size:.75rem;">
                                {{ $tipeLabel[$booking->tipe_antar_jemput] ?? '' }}
                            </span>
                            <span class="text-danger">*</span>
                        </label>
                        @if($booking->alamat_jemput)
                        <small class="text-muted d-block"><i class="fas fa-map-marker-alt mr-1"></i>Jemput: {{ $booking->alamat_jemput }}</small>
                        @endif
                        @if($booking->alamat_antar)
                        <small class="text-muted d-block"><i class="fas fa-map-marker-alt mr-1"></i>Antar: {{ $booking->alamat_antar }}</small>
                        @endif
                        <div class="input-group mt-1">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="number" name="biaya_antar_jemput" id="inputBiayaAntar"
                                class="form-control @error('biaya_antar_jemput') is-invalid @enderror"
                                min="0" step="500"
                                value="{{ old('biaya_antar_jemput', $prefillBiaya) }}">
                        </div>
                        @if($hargaDefault > 0)
                        <small class="text-muted">
                            Tarif standar: <strong>Rp {{ number_format($hargaDefault, 0, ',', '.') }}</strong>.
                            Sesuaikan jika jarak berbeda.
                        </small>
                        @else
                        <small class="text-muted">Tentukan tarif sesuai jarak lokasi pelanggan.</small>
                        @endif
                        @error('biaya_antar_jemput')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                    <input type="hidden" name="biaya_antar_jemput" value="0">
                    @endif

                    {{-- Pewangi --}}
                    <div class="form-group">
                        <label><i class="fas fa-spray-can mr-1"></i>Pewangi (Opsional)</label>
                        <select name="id_pewangi" class="form-control">
                            <option value="">— Tidak Pakai Pewangi —</option>
                            @foreach($stokBarangs as $s)
                            <option value="{{ $s->id_barang }}" {{ old('id_pewangi') == $s->id_barang ? 'selected' : '' }}>
                                {{ $s->nama_barang }} (Stok: {{ $s->stok }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Catatan Admin --}}
                    <div class="form-group">
                        <label><i class="fas fa-comment mr-1"></i>Catatan Admin (Opsional)</label>
                        <textarea name="catatan_admin" class="form-control" rows="2"
                            placeholder="Misal: cucian sudah diterima, ada noda pada kemeja putih, dll.">{{ old('catatan_admin') }}</textarea>
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success btn-lg" id="btnKonfirmasi">
                        <i class="fas fa-check-circle mr-1"></i> Konfirmasi & Buat Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let rowCount = {{ $booking->layanans->isNotEmpty() ? $booking->layanans->count() : 1 }};
    const dpBayar = {{ ($booking->dp_bayar ?? 0) }};

    function getBiayaAntar() {
        return parseFloat($('#inputBiayaAntar').val()) || 0;
    }

    // =====================
    // Hitung ulang semua baris
    // =====================
    function hitungSemua() {
        let totalBerat = 0;
        let totalHargaLayanan = 0;

        $('.detail-row-item').each(function () {
            const row = $(this).data('row-id');
            const hargaPerKg = parseFloat($(this).find('.layanan-select option:selected').data('harga')) || 0;
            const berat = parseFloat($(this).find('.berat-input').val()) || 0;
            const subtotal = berat * hargaPerKg;

            totalBerat += berat;
            totalHargaLayanan += subtotal;

            $(this).find('.subtotal-display').text('Rp ' + formatAngka(subtotal));
        });

        const totalTagihan = totalHargaLayanan + getBiayaAntar();
        const sisa = Math.max(0, totalTagihan - dpBayar);

        $('#totalBeratDisplay').text(totalBerat.toFixed(2) + ' kg');
        $('#rekapBerat').text(totalBerat.toFixed(2) + ' kg');
        $('#rekapHargaLayanan').text('Rp ' + formatAngka(totalHargaLayanan));
        $('#rekapBiayaAntar').text('Rp ' + formatAngka(getBiayaAntar()));
        $('#rekapTotal_').text('Rp ' + formatAngka(totalTagihan));
        $('#rekapSisa').text('Rp ' + formatAngka(sisa));
    }

    function formatAngka(n) {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Event: layanan berubah atau berat berubah
    $(document).on('change input', '.layanan-select, .berat-input', function () {
        hitungSemua();
    });

    // Event: biaya antar berubah
    $(document).on('input', '#inputBiayaAntar', function () {
        hitungSemua();
    });

    // =====================
    // Tambah Baris Layanan
    // =====================
    $('#btnTambahLayanan').on('click', function () {
        const idx = rowCount++;
        const html = `
        <div class="detail-row-item" data-row-id="${idx}" id="row-${idx}">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label class="small text-muted">Jenis Layanan <span class="text-danger">*</span></label>
                    <select name="details[${idx}][id_layanan]" class="form-control form-control-sm layanan-select" required data-row="${idx}">
                        <option value="">— Pilih Layanan —</option>
                        @foreach($layanans as $l)
                        <option value="{{ $l->id_layanan }}" data-harga="{{ $l->harga_per_kg }}">
                            {{ $l->nama_layanan }} — Rp {{ number_format($l->harga_per_kg, 0, ',', '.') }}/kg
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small text-muted">Berat Asli (kg) <span class="text-danger">*</span></label>
                    <input type="number" name="details[${idx}][berat]" class="form-control form-control-sm berat-asli-input berat-input"
                        step="0.1" min="0.1" placeholder="0.0" required data-row="${idx}">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted">Subtotal</label>
                    <div class="subtotal-display" id="subtotal-${idx}">Rp 0</div>
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row" data-row="${idx}" title="Hapus baris">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>`;
        $('#detailContainer').append(html);
        updateRemoveButtons();
        hitungSemua();
    });

    // =====================
    // Hapus Baris Layanan
    // =====================
    $(document).on('click', '.btn-remove-row', function () {
        $(this).closest('.detail-row-item').remove();
        updateRemoveButtons();
        hitungSemua();
    });

    function updateRemoveButtons() {
        const rows = $('.detail-row-item');
        rows.find('.btn-remove-row').toggle(rows.length > 1);
    }

    // Set data-row-id pada row pertama
    $('#row-0').attr('data-row-id', 0);

    // Hitung awal
    hitungSemua();

    // =====================
    // Konfirmasi Submit
    // =====================
    $('#formKonfirmasi').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const totalBerat = parseFloat($('#rekapBerat').text()) || 0;
        const estimasi = {{ ($booking->estimasi_berat ?? 0) }};
        const selisih = Math.abs(totalBerat - estimasi);
        const persen = estimasi > 0 ? (selisih / estimasi * 100) : 0;

        let warningMsg = '';
        if (totalBerat <= 0) {
            Swal.fire('Perhatian', 'Total berat asli harus lebih dari 0 kg.', 'error');
            return;
        }
        if (persen > 30) {
            warningMsg = `Selisih berat cukup besar: estimasi <b>${estimasi} kg</b>, berat asli <b>${totalBerat.toFixed(2)} kg</b> (selisih ${persen.toFixed(0)}%).<br><br>Yakin ingin melanjutkan?`;
        } else {
            warningMsg = `Konfirmasi booking dengan total berat asli <b>${totalBerat.toFixed(2)} kg</b>?<br><small class="text-muted">Data akan langsung masuk ke transaksi.</small>`;
        }

        Swal.fire({
            title: 'Konfirmasi Booking',
            html: warningMsg,
            icon: persen > 30 ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Konfirmasi!',
            cancelButtonText: 'Periksa Lagi'
        }).then(r => {
            if (r.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
