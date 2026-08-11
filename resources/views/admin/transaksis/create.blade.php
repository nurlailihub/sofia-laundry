@extends('layouts.admin')

@section('title', 'Tambah Transaksi')

@section('page-title', 'Tambah Transaksi Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.transaksis.index') }}">Buat Transaksi Laundry</a></li>
    <li class="breadcrumb-item active">Tambah Buat Transaksi Laundry</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cash-register mr-1"></i> Form Tambah Buat Transaksi Laundry</h3>
            </div>
            <form action="{{ route('admin.transaksis.store') }}" method="POST" id="formTransaksi">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pelanggan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="hidden" name="id_pelanggan" id="selectedPelangganId" class="@error('id_pelanggan') is-invalid @enderror" required value="{{ old('id_pelanggan') }}">
                                    <input type="text" id="selectedPelangganNama" class="form-control" placeholder="Klik untuk cari pelanggan..." readonly required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCariPelanggan">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    @error('id_pelanggan')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                    value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                                @error('tanggal_masuk')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pewangi</label>
                                <select name="id_pewangi" class="form-control @error('id_pewangi') is-invalid @enderror">
                                    <option value="">Tidak Pakai Pewangi</option>
                                    @foreach($stok_barangs as $barang)
                                        <option value="{{ $barang->id_barang }}" {{ old('id_pewangi') == $barang->id_barang ? 'selected' : '' }}>
                                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_pewangi')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estimasi Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                    value="{{ old('tanggal_selesai') }}">
                                @error('tanggal_selesai')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="diambil" {{ old('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-primary mb-3"><i class="fas fa-list-alt mr-1"></i> Detail Layanan</h5>
                    <div id="detailContainer">
                        <div class="detail-item mb-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <label>Layanan <span class="text-danger">*</span></label>
                                    <select name="details[0][id_layanan]" class="form-control layanan-select" required>
                                        <option value="">Pilih Layanan</option>
                                        @foreach($layanans as $layanan)
                                            <option value="{{ $layanan->id_layanan }}" data-harga="{{ $layanan->harga_per_kg }}">
                                                {{ $layanan->nama_layanan }} (Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}/kg)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Berat (Kg) <span class="text-danger">*</span></label>
                                    <input type="number" name="details[0][berat]" class="form-control berat-input" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-3">
                                    <label>Subtotal</label>
                                    <input type="number" name="details[0][subtotal]" class="form-control subtotal-input" readonly>
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm btn-block remove-detail" style="display:none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="addDetail">
                        <i class="fas fa-plus"></i> Tambah Layanan
                    </button>

                    <hr>
                    {{-- ── Antar / Jemput ── --}}
                    <h5 class="text-primary mb-3"><i class="fas fa-truck mr-1"></i> Antar / Jemput</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipe Layanan Antar/Jemput</label>
                                <select name="tipe_antar_jemput" id="tipeAntarJemput" class="form-control @error('tipe_antar_jemput') is-invalid @enderror">
                                    <option value="none"     {{ old('tipe_antar_jemput','none') == 'none'     ? 'selected' : '' }}>Tidak Ada (Pelanggan Datang Sendiri)</option>
                                    <option value="pickup"   {{ old('tipe_antar_jemput') == 'pickup'   ? 'selected' : '' }}>Dijemput Admin</option>
                                    <option value="delivery" {{ old('tipe_antar_jemput') == 'delivery' ? 'selected' : '' }}>Diantar Admin</option>
                                    <option value="both"     {{ old('tipe_antar_jemput') == 'both'     ? 'selected' : '' }}>Jemput & Antar</option>
                                </select>
                                @error('tipe_antar_jemput')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6" id="wrapBiayaAntar" style="{{ old('tipe_antar_jemput','none') == 'none' ? 'display:none;' : '' }}">
                            <div class="form-group">
                                <label>Biaya Antar/Jemput <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="biaya_antar_jemput" id="biayaAntarJemput"
                                        class="form-control @error('biaya_antar_jemput') is-invalid @enderror"
                                        min="0" step="500"
                                        value="{{ old('biaya_antar_jemput', 0) }}"
                                        placeholder="0">
                                </div>
                                @error('biaya_antar_jemput')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Total Berat (Kg)</label>
                                <input type="number" name="total_berat" id="totalBerat" class="form-control" step="0.01" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Subtotal Layanan</label>
                                <input type="number" id="subtotalLayanan" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold text-success">Total Harga (+ Antar/Jemput)</label>
                                <input type="number" name="total_harga" id="totalHarga" class="form-control font-weight-bold" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.transaksis.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="fas fa-save mr-1"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<div class="modal fade" id="modalCariPelanggan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-search mr-1"></i> Cari Pelanggan</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" id="searchPelangganInput" class="form-control" placeholder="Ketik nama atau no HP pelanggan..." autofocus>
                </div>
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-bordered table-hover table-sm" id="tableCariPelanggan">
                        <thead class="thead-light">
                            <tr>
                                <th width="10%">ID</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelanggans as $p)
                            <tr class="row-pelanggan" data-id="{{ $p->id_pelanggan }}" data-nama="{{ $p->nama_pelanggan }}">
                                <td>#{{ $p->id_pelanggan }}</td>
                                <td>{{ $p->nama_pelanggan }}</td>
                                <td>{{ $p->no_hp }}</td>
                                <td>{{ $p->alamat }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm btn-pilih-pelanggan"
                                        data-id="{{ $p->id_pelanggan }}" data-nama="{{ $p->nama_pelanggan }}">
                                        <i class="fas fa-check"></i> Pilih
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

@push('scripts')
<script>
var detailIndex = 1;

// Data layanan dari server untuk dipakai di JS
var layanansData = {
    @foreach($layanans as $layanan)
    {{ $layanan->id_layanan }}: { harga: {{ $layanan->harga_per_kg }}, nama: "{{ $layanan->nama_layanan }}" },
    @endforeach
};

$(document).ready(function() {

    // ── Cari Pelanggan ──
    $('#searchPelangganInput').on('keyup', function() {
        var keyword = $(this).val().toLowerCase();
        $('#tableCariPelanggan tbody tr').each(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(keyword) !== -1);
        });
    });

    $(document).on('click', '.btn-pilih-pelanggan', function() {
        $('#selectedPelangganId').val($(this).data('id'));
        $('#selectedPelangganNama').val('[' + $(this).data('id') + '] ' + $(this).data('nama'));
        $('#modalCariPelanggan').modal('hide');
        $('#searchPelangganInput').val('');
        $('#tableCariPelanggan tbody tr').show();
    });

    $('#modalCariPelanggan').on('shown.bs.modal', function() { $('#searchPelangganInput').focus(); });
    $('#modalCariPelanggan').on('hidden.bs.modal', function() {
        $('#searchPelangganInput').val('');
        $('#tableCariPelanggan tbody tr').show();
    });

    @if(old('id_pelanggan'))
    (function() {
        var row = $('#tableCariPelanggan tr[data-id="{{ old('id_pelanggan') }}"]');
        if (row.length) $('#selectedPelangganNama').val('[' + row.data('id') + '] ' + row.data('nama'));
    })();
    @endif

    // ── Tambah Baris Layanan ──
    $('#addDetail').on('click', function() {
        var options = '';
        $.each(layanansData, function(id, data) {
            options += '<option value="' + id + '" data-harga="' + data.harga + '">'
                     + data.nama + ' (Rp ' + data.harga.toLocaleString('id-ID') + '/kg)</option>';
        });

        var html = '<div class="detail-item mb-3 border rounded p-2 bg-light">'
            + '<div class="row align-items-end">'
            + '<div class="col-md-5"><label>Layanan <span class="text-danger">*</span></label>'
            + '<select name="details[' + detailIndex + '][id_layanan]" class="form-control layanan-select" required>'
            + '<option value="">Pilih Layanan</option>' + options + '</select></div>'
            + '<div class="col-md-3"><label>Berat (Kg) <span class="text-danger">*</span></label>'
            + '<input type="number" name="details[' + detailIndex + '][berat]" class="form-control berat-input" step="0.01" min="0" required></div>'
            + '<div class="col-md-3"><label>Subtotal</label>'
            + '<input type="number" name="details[' + detailIndex + '][subtotal]" class="form-control subtotal-input bg-white" readonly></div>'
            + '<div class="col-md-1"><label>&nbsp;</label>'
            + '<button type="button" class="btn btn-danger btn-sm btn-block remove-detail"><i class="fas fa-trash"></i></button></div>'
            + '</div></div>';

        $('#detailContainer').append(html);
        detailIndex++;

        // Tampilkan tombol hapus di baris pertama jika sudah ada lebih dari 1
        toggleRemoveBtn();
    });

    // ── Hapus Baris Layanan ──
    $(document).on('click', '.remove-detail', function() {
        $(this).closest('.detail-item').remove();
        toggleRemoveBtn();
        calculateTotal();
    });

    function toggleRemoveBtn() {
        var items = $('.detail-item');
        if (items.length > 1) {
            items.find('.remove-detail').show();
        } else {
            items.find('.remove-detail').hide();
        }
    }

    // ── Hitung subtotal per baris ──
    $(document).on('change input', '.layanan-select, .berat-input', function() {
        var row    = $(this).closest('.detail-item');
        var harga  = parseFloat(row.find('.layanan-select option:selected').data('harga')) || 0;
        var berat  = parseFloat(row.find('.berat-input').val()) || 0;
        row.find('.subtotal-input').val((harga * berat).toFixed(0));
        calculateTotal();
    });

    // ── Toggle biaya antar/jemput ──
    $('#tipeAntarJemput').on('change', function() {
        if ($(this).val() === 'none') {
            $('#wrapBiayaAntar').slideUp(150);
            $('#biayaAntarJemput').val(0);
        } else {
            $('#wrapBiayaAntar').slideDown(150);
            $('#biayaAntarJemput').focus();
        }
        calculateTotal();
    });

    $('#biayaAntarJemput').on('input', function() {
        calculateTotal();
    });

    // ── Kalkulasi Total ──
    function calculateTotal() {
        var totalBerat      = 0;
        var subtotalLayanan = 0;

        $('.berat-input').each(function() {
            totalBerat += parseFloat($(this).val()) || 0;
        });
        $('.subtotal-input').each(function() {
            subtotalLayanan += parseFloat($(this).val()) || 0;
        });

        var biayaAntar = ($('#tipeAntarJemput').val() !== 'none')
            ? (parseFloat($('#biayaAntarJemput').val()) || 0)
            : 0;

        var totalHarga = subtotalLayanan + biayaAntar;

        $('#totalBerat').val(totalBerat.toFixed(2));
        $('#subtotalLayanan').val(subtotalLayanan.toFixed(0));
        $('#totalHarga').val(totalHarga.toFixed(0));
    }

    // Init saat halaman load
    toggleRemoveBtn();
    calculateTotal();
});
</script>
@endpush
