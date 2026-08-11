@extends('layouts.admin')

@section('title', 'Tambah Transaksi')
@section('page-title', 'Tambah Transaksi Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.transaksis.index') }}">Transaksi Laundry</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@push('styles')
<style>
.section-title {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .6px;
    font-weight: 700;
    color: #005F73;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}
.detail-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: .875rem 1rem;
    margin-bottom: .6rem;
}
.total-bar {
    background: linear-gradient(135deg, #005F73, #2BB1B1);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    color: #fff;
}
.total-bar label { color: rgba(255,255,255,.75); font-size: .8rem; margin-bottom: .2rem; }
.total-bar .val { font-size: 1.1rem; font-weight: 700; }
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

<div class="row justify-content-center">
    <div class="col-xl-10">
        <form action="{{ route('admin.transaksis.store') }}" method="POST" id="formTransaksi">
            @csrf

            {{-- ===== INFO DASAR ===== --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body pb-2">
                    <div class="section-title"><i class="fas fa-info-circle"></i> Informasi Dasar</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Pelanggan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="hidden" name="id_pelanggan" id="selectedPelangganId"
                                        value="{{ old('id_pelanggan') }}" required>
                                    <input type="text" id="selectedPelangganNama" class="form-control @error('id_pelanggan') is-invalid @enderror"
                                        placeholder="Klik tombol cari untuk pilih pelanggan..." readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCariPelanggan">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    @error('id_pelanggan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold small">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_masuk"
                                    class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                                @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold small">Estimasi Selesai</label>
                                <input type="date" name="tanggal_selesai"
                                    class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                    value="{{ old('tanggal_selesai') }}">
                                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Pewangi</label>
                                <select name="id_pewangi" class="form-control @error('id_pewangi') is-invalid @enderror">
                                    <option value="">— Tidak Pakai Pewangi —</option>
                                    @foreach($stok_barangs as $barang)
                                    <option value="{{ $barang->id_barang }}" {{ old('id_pewangi') == $barang->id_barang ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_pewangi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="proses"  {{ old('status','proses') == 'proses'  ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="diambil" {{ old('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== DETAIL LAYANAN ===== --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body pb-2">
                    <div class="section-title"><i class="fas fa-tshirt"></i> Detail Layanan</div>

                    <div id="detailContainer">
                        <div class="detail-item">
                            <div class="row align-items-end">
                                <div class="col-md-5">
                                    <label class="small font-weight-bold">Layanan <span class="text-danger">*</span></label>
                                    <select name="details[0][id_layanan]" class="form-control form-control-sm layanan-select" required>
                                        <option value="">— Pilih Layanan —</option>
                                        @foreach($layanans as $layanan)
                                        <option value="{{ $layanan->id_layanan }}" data-harga="{{ $layanan->harga_per_kg }}">
                                            {{ $layanan->nama_layanan }} (Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}/kg)
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Berat (Kg) <span class="text-danger">*</span></label>
                                    <input type="number" name="details[0][berat]"
                                        class="form-control form-control-sm berat-input"
                                        step="0.01" min="0" placeholder="0.00" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="small font-weight-bold">Subtotal</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="number" name="details[0][subtotal]"
                                            class="form-control subtotal-input bg-white" readonly>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-detail" style="display:none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-success btn-sm mt-2" id="addDetail">
                        <i class="fas fa-plus mr-1"></i>Tambah Layanan
                    </button>
                </div>
            </div>

            {{-- ===== ANTAR / JEMPUT ===== --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body pb-2">
                    <div class="section-title"><i class="fas fa-truck"></i> Layanan Antar / Jemput</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Tipe Antar/Jemput</label>
                                <select name="tipe_antar_jemput" id="tipeAntarJemput"
                                    class="form-control @error('tipe_antar_jemput') is-invalid @enderror">
                                    <option value="none"     {{ old('tipe_antar_jemput','none') == 'none'     ? 'selected' : '' }}>Tidak Ada — Pelanggan Datang Sendiri</option>
                                    <option value="pickup"   {{ old('tipe_antar_jemput') == 'pickup'   ? 'selected' : '' }}>Dijemput Admin</option>
                                    <option value="delivery" {{ old('tipe_antar_jemput') == 'delivery' ? 'selected' : '' }}>Diantar Admin</option>
                                    <option value="both"     {{ old('tipe_antar_jemput') == 'both'     ? 'selected' : '' }}>Jemput & Antar</option>
                                </select>
                                @error('tipe_antar_jemput')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6" id="wrapBiayaAntar"
                            style="{{ old('tipe_antar_jemput','none') == 'none' ? 'display:none;' : '' }}">
                            <div class="form-group">
                                <label class="font-weight-bold small">Biaya Antar/Jemput <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="biaya_antar_jemput" id="biayaAntarJemput"
                                        class="form-control @error('biaya_antar_jemput') is-invalid @enderror"
                                        min="0" step="500" placeholder="0"
                                        value="{{ old('biaya_antar_jemput', 0) }}">
                                </div>
                                @error('biaya_antar_jemput')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div id="wrapAlamatJemput"
                        style="{{ in_array(old('tipe_antar_jemput'), ['pickup','both']) ? '' : 'display:none;' }}">
                        <div class="form-group">
                            <label class="font-weight-bold small">
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i>Alamat Penjemputan <span class="text-danger">*</span>
                            </label>
                            <textarea name="alamat_jemput" rows="2"
                                class="form-control @error('alamat_jemput') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap penjemputan cucian...">{{ old('alamat_jemput') }}</textarea>
                            @error('alamat_jemput')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div id="wrapAlamatKirim"
                        style="{{ in_array(old('tipe_antar_jemput'), ['delivery','both']) ? '' : 'display:none;' }}">
                        <div class="form-group">
                            <label class="font-weight-bold small">
                                <i class="fas fa-map-marker-alt text-primary mr-1"></i>Alamat Pengantaran <span class="text-danger">*</span>
                            </label>
                            <textarea name="alamat_antar" rows="2"
                                class="form-control @error('alamat_antar') is-invalid @enderror"
                                placeholder="Masukkan alamat lengkap tujuan pengantaran laundry...">{{ old('alamat_antar') }}</textarea>
                            @error('alamat_antar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== RINGKASAN TOTAL ===== --}}
            <div class="total-bar mb-3">
                <div class="row text-center">
                    <div class="col-md-4 border-right" style="border-color:rgba(255,255,255,.2)!important;">
                        <label>Total Berat</label>
                        <div class="val" id="displayBerat">0.00 Kg</div>
                        <input type="number" name="total_berat" id="totalBerat" style="display:none;" step="0.01">
                    </div>
                    <div class="col-md-4 border-right" style="border-color:rgba(255,255,255,.2)!important;">
                        <label>Subtotal Layanan</label>
                        <div class="val" id="displaySubtotal">Rp 0</div>
                        <input type="number" id="subtotalLayanan" style="display:none;">
                    </div>
                    <div class="col-md-4">
                        <label>Total Tagihan</label>
                        <div class="val" id="displayTotal" style="font-size:1.3rem;">Rp 0</div>
                        <input type="number" name="total_harga" id="totalHarga" style="display:none;">
                    </div>
                </div>
            </div>

            {{-- ===== TOMBOL AKSI ===== --}}
            <div class="d-flex justify-content-between mb-4">
                <a href="{{ route('admin.transaksis.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save mr-1"></i> Simpan Transaksi
                </button>
            </div>

        </form>
    </div>
</div>

{{-- Modal Cari Pelanggan --}}
<div class="modal fade" id="modalCariPelanggan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#005F73,#2BB1B1);">
                <h5 class="modal-title text-white"><i class="fas fa-search mr-2"></i>Cari Pelanggan</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="text" id="searchPelangganInput" class="form-control mb-3"
                    placeholder="Ketik nama atau no HP..." autofocus>
                <div style="max-height:350px;overflow-y:auto;">
                    <table class="table table-bordered table-hover table-sm" id="tableCariPelanggan">
                        <thead class="thead-light">
                            <tr>
                                <th width="10%">ID</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggans as $p)
                            <tr data-id="{{ $p->id_pelanggan }}" data-nama="{{ $p->nama_pelanggan }}">
                                <td>#{{ $p->id_pelanggan }}</td>
                                <td>{{ $p->nama_pelanggan }}</td>
                                <td>{{ $p->no_hp }}</td>
                                <td>{{ $p->alamat }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-pilih-pelanggan"
                                        data-id="{{ $p->id_pelanggan }}" data-nama="{{ $p->nama_pelanggan }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
var detailIndex = 1;
var layanansData = {
    @foreach($layanans as $layanan)
    {{ $layanan->id_layanan }}: { harga: {{ $layanan->harga_per_kg }}, nama: "{{ addslashes($layanan->nama_layanan) }}" },
    @endforeach
};

function fmt(n) {
    return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

$(document).ready(function() {

    $('#searchPelangganInput').on('keyup', function() {
        var kw = $(this).val().toLowerCase();
        $('#tableCariPelanggan tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().includes(kw));
        });
    });

    $(document).on('click', '.btn-pilih-pelanggan', function() {
        $('#selectedPelangganId').val($(this).data('id'));
        $('#selectedPelangganNama').val('[#' + $(this).data('id') + '] ' + $(this).data('nama'));
        $('#modalCariPelanggan').modal('hide');
    });

    $('#modalCariPelanggan').on('shown.bs.modal', function() { $('#searchPelangganInput').focus(); });
    $('#modalCariPelanggan').on('hidden.bs.modal', function() {
        $('#searchPelangganInput').val('');
        $('#tableCariPelanggan tbody tr').show();
    });

    @if(old('id_pelanggan'))
    var oldRow = $('#tableCariPelanggan tr[data-id="{{ old('id_pelanggan') }}"]');
    if (oldRow.length) $('#selectedPelangganNama').val('[#' + oldRow.data('id') + '] ' + oldRow.data('nama'));
    @endif

    $('#addDetail').on('click', function() {
        var opts = '<option value="">— Pilih Layanan —</option>';
        $.each(layanansData, function(id, d) {
            opts += '<option value="' + id + '" data-harga="' + d.harga + '">' + d.nama
                  + ' (Rp ' + d.harga.toLocaleString('id-ID') + '/kg)</option>';
        });

        var html = '<div class="detail-item">'
            + '<div class="row align-items-end">'
            + '<div class="col-md-5"><label class="small font-weight-bold">Layanan <span class="text-danger">*</span></label>'
            + '<select name="details[' + detailIndex + '][id_layanan]" class="form-control form-control-sm layanan-select" required>' + opts + '</select></div>'
            + '<div class="col-md-3"><label class="small font-weight-bold">Berat (Kg) <span class="text-danger">*</span></label>'
            + '<input type="number" name="details[' + detailIndex + '][berat]" class="form-control form-control-sm berat-input" step="0.01" min="0" placeholder="0.00" required></div>'
            + '<div class="col-md-3"><label class="small font-weight-bold">Subtotal</label>'
            + '<div class="input-group input-group-sm"><div class="input-group-prepend"><span class="input-group-text">Rp</span></div>'
            + '<input type="number" name="details[' + detailIndex + '][subtotal]" class="form-control subtotal-input bg-white" readonly></div></div>'
            + '<div class="col-md-1 text-center"><button type="button" class="btn btn-outline-danger btn-sm remove-detail"><i class="fas fa-times"></i></button></div>'
            + '</div></div>';

        $('#detailContainer').append(html);
        detailIndex++;
        toggleRemoveBtn();
    });

    $(document).on('click', '.remove-detail', function() {
        $(this).closest('.detail-item').remove();
        toggleRemoveBtn();
        calculateTotal();
    });

    function toggleRemoveBtn() {
        var items = $('.detail-item');
        items.find('.remove-detail').toggle(items.length > 1);
    }

    $(document).on('change input', '.layanan-select, .berat-input', function() {
        var row   = $(this).closest('.detail-item');
        var harga = parseFloat(row.find('.layanan-select option:selected').data('harga')) || 0;
        var berat = parseFloat(row.find('.berat-input').val()) || 0;
        row.find('.subtotal-input').val((harga * berat).toFixed(0));
        calculateTotal();
    });

    $('#tipeAntarJemput').on('change', function() {
        var val = $(this).val();
        if (val === 'none') {
            $('#wrapBiayaAntar').slideUp(150);
            $('#biayaAntarJemput').val(0);
            $('#wrapAlamatJemput').slideUp(150);
            $('#wrapAlamatKirim').slideUp(150);
        } else {
            $('#wrapBiayaAntar').slideDown(150);
            if (val === 'pickup') {
                $('#wrapAlamatJemput').slideDown(150);
                $('#wrapAlamatKirim').slideUp(150);
            } else if (val === 'delivery') {
                $('#wrapAlamatJemput').slideUp(150);
                $('#wrapAlamatKirim').slideDown(150);
            } else if (val === 'both') {
                $('#wrapAlamatJemput').slideDown(150);
                $('#wrapAlamatKirim').slideDown(150);
            }
        }
        calculateTotal();
    });

    $('#biayaAntarJemput').on('input', calculateTotal);

    function calculateTotal() {
        var berat = 0, subtotal = 0;
        $('.berat-input').each(function()    { berat    += parseFloat($(this).val()) || 0; });
        $('.subtotal-input').each(function() { subtotal += parseFloat($(this).val()) || 0; });

        var biaya = ($('#tipeAntarJemput').val() !== 'none')
            ? (parseFloat($('#biayaAntarJemput').val()) || 0) : 0;

        var total = subtotal + biaya;

        $('#totalBerat').val(berat.toFixed(2));
        $('#subtotalLayanan').val(subtotal.toFixed(0));
        $('#totalHarga').val(total.toFixed(0));
        $('#displayBerat').text(berat.toFixed(2) + ' Kg');
        $('#displaySubtotal').text(fmt(subtotal));
        $('#displayTotal').text(fmt(total));
    }

    toggleRemoveBtn();
    calculateTotal();
});
</script>
@endpush
