@extends('layouts.admin')

@section('title', 'Laporan Per Tahun')
@section('page-title', 'Laporan Per Tahun')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Laporan Per Tahun</li>
@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Filter Laporan Per Tahun</h3>
    </div>
    <div class="card-body">
        <form id="formFilter" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2 font-weight-bold">Tahun</label>
                <select name="tahun" id="tahun" class="form-control">
                    @for($y = date('Y'); $y >= date('Y') - 10; $y--)
                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary mr-2">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <button type="button" class="btn btn-success" id="btnCetakPdf">
                <i class="fas fa-file-pdf mr-1"></i>Cetak PDF
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="statTotalTransaksi">0</h3>
                <p>Total Transaksi</p>
            </div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
        </div>
    </div>
    <div class="col-lg-6 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="statTotalPendapatan">Rp 0</h3>
                <p>Total Pendapatan</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">Data Laporan Tahun <span id="labelTahun" class="badge badge-primary ml-2"></span></h3>
    </div>
    <div class="card-body">
        <table id="tableBulan" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th>Bulan</th>
                    <th class="text-right">Total Transaksi</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody id="tbodyBulan">
            </tbody>
            <tfoot id="tfootBulan" style="display:none;">
                <tr style="font-weight:bold;background:#f0fdf4;">
                    <td colspan="2" class="text-right">Jumlah Keseluruhan</td>
                    <td class="text-right" id="footTransaksi">0</td>
                    <td class="text-right" id="footPendapatan">Rp 0</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#tableBulan').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        paging: false, info: false, searching: false, ordering: false,
    });

    loadData();

    $('#formFilter').on('submit', function(e) { e.preventDefault(); loadData(); });

    $('#btnCetakPdf').on('click', function() {
        var params = new URLSearchParams($('#formFilter').serialize());
        window.open('{{ route("admin.laporan.pertahun.cetak") }}?' + params.toString(), '_blank');
    });

    function loadData() {
        var tahun = $('#tahun').val();
        $('#labelTahun').text(tahun || 'Semua');

        $.ajax({
            url: '{{ route("admin.laporan.pertahun.data") }}',
            method: 'GET',
            data: $('#formFilter').serialize(),
            success: function(res) {
                if (!res.success) return;

                var stat = res.data.statistik;
                $('#statTotalTransaksi').text(stat.total_transaksi);
                $('#statTotalPendapatan').text('Rp ' + fmt(stat.total_pendapatan));

                table.clear();
                var totalTrx = 0, totalPend = 0;

                if (res.data.perBulan.length > 0) {
                    res.data.perBulan.forEach(function(row, i) {
                        totalTrx  += row.total_transaksi;
                        totalPend += parseFloat(row.total_pendapatan || 0);
                        table.row.add([
                            i + 1,
                            row.bulan,
                            row.total_transaksi,
                            'Rp ' + fmt(row.total_pendapatan || 0)
                        ]);
                    });
                    $('#tfootBulan').show();
                    $('#footTransaksi').text(totalTrx);
                    $('#footPendapatan').text('Rp ' + fmt(totalPend));
                } else {
                    $('#tfootBulan').hide();
                }

                table.draw();
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Gagal memuat data laporan.' });
            }
        });
    }

    function fmt(n) {
        return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
});
</script>
@endpush
