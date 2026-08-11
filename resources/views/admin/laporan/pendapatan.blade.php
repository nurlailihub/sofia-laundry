@extends('layouts.admin')

@section('title', 'Laporan Pendapatan')
@section('page-title', 'Laporan Pendapatan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Laporan Pendapatan</li>
@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Filter Laporan Pendapatan</h3>
    </div>
    <div class="card-body">
        <form id="formFilter">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                            value="{{ date('Y-m-01') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                            value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status Bayar</label>
                        <select name="status_bayar" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="belum">Belum Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search mr-1"></i>Filter
                        </button>
                        <button type="button" class="btn btn-success" id="btnCetakPdf">
                            <i class="fas fa-file-pdf mr-1"></i>Cetak PDF
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3 id="statTotalTrx">0</h3><p>Total Transaksi</p></div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3 id="statTotalTagihan">Rp 0</h3><p>Total Tagihan</p></div>
            <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3 id="statTerbayar">Rp 0</h3><p>Sudah Dibayar</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3 id="statLunas">0</h3><p>Lunas / Belum: <span id="statBelum">0</span></p></div>
            <div class="icon"><i class="fas fa-balance-scale"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Detail Pendapatan</h3>
    </div>
    <div class="card-body">
        <table id="tablePendapatan" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th class="text-right">Total Tagihan</th>
                    <th class="text-right">Dibayar</th>
                    <th class="text-center">Metode</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody id="tbodyPendapatan"></tbody>
            <tfoot id="tfootPendapatan" style="display:none;">
                <tr style="font-weight:bold;background:#f0fdf4;">
                    <td colspan="4" class="text-right">Total</td>
                    <td class="text-right" id="footTagihan">Rp 0</td>
                    <td class="text-right" id="footTerbayar">Rp 0</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#tablePendapatan').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        order: [[2, 'desc']],
        columnDefs: [
            { className: 'text-right', targets: [4, 5] },
            { className: 'text-center', targets: [6, 7] },
        ],
    });

    loadData();
    $('#formFilter').on('submit', function(e) { e.preventDefault(); loadData(); });

    $('#btnCetakPdf').on('click', function() {
        var params = new URLSearchParams($('#formFilter').serialize());
        window.open('{{ route("admin.laporan.pendapatan.cetak") }}?' + params.toString(), '_blank');
    });

    function loadData() {
        $.ajax({
            url: '{{ route("admin.laporan.pendapatan.data") }}',
            method: 'GET',
            data: $('#formFilter').serialize(),
            success: function(res) {
                if (!res.success) return;
                var s = res.data.statistik;
                $('#statTotalTrx').text(s.total_transaksi);
                $('#statTotalTagihan').text('Rp ' + fmt(s.total_tagihan));
                $('#statTerbayar').text('Rp ' + fmt(s.total_terbayar));
                $('#statLunas').text(s.jumlah_lunas);
                $('#statBelum').text(s.jumlah_belum);

                table.clear();
                var totTagihan = 0, totBayar = 0;

                res.data.rows.forEach(function(r, i) {
                    totTagihan += r.total_tagihan;
                    totBayar   += r.jumlah_bayar;

                    var statusBadge = r.status_bayar === 'lunas'
                        ? '<span class="badge badge-success">Lunas</span>'
                        : '<span class="badge badge-warning">Belum Lunas</span>';
                    var metodeLabel = { cash: 'Cash', transfer: 'Transfer', qris: 'QRIS' };

                    table.row.add([
                        i + 1,
                        '#' + String(r.id_transaksi).padStart(6, '0'),
                        r.tanggal,
                        r.pelanggan,
                        'Rp ' + fmt(r.total_tagihan),
                        'Rp ' + fmt(r.jumlah_bayar),
                        metodeLabel[r.metode_bayar] || r.metode_bayar,
                        statusBadge,
                    ]);
                });

                if (res.data.rows.length > 0) {
                    $('#tfootPendapatan').show();
                    $('#footTagihan').text('Rp ' + fmt(totTagihan));
                    $('#footTerbayar').text('Rp ' + fmt(totBayar));
                } else {
                    $('#tfootPendapatan').hide();
                }

                table.draw();
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat data.' });
            }
        });
    }

    function fmt(n) {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
});
</script>
@endpush
