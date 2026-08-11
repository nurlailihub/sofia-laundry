@extends('layouts.admin')

@section('title', 'Cetak Laporan Pertahun')

@section('page-title', 'Cetak Laporan Pertahun')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Cetak Laporan Pertahun</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Laporan Pertahun</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" id="btnCetakPdf">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="formFilter">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select name="tahun" id="tahun" class="form-control">
                                    <option value="">Semua Tahun</option>
                                    @for($y = date('Y'); $y >= date('Y') - 10; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <button type="button" class="btn btn-secondary" id="btnReset">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Keseluruhan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="statTotalTransaksi">0</h3>
                                <p>Total Transaksi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="statTotalPendapatan">Rp 0</h3>
                                <p>Total Pendapatan</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="statTotalBerat">0</h3>
                                <p>Total Berat (Kg)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-weight"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Laporan Pertahun</h3>
            </div>
            <div class="card-body">
                <table id="tableBulan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Tahun</th>
                            <th>Bulan</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Berat (Kg)</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyBulan">
                    </tbody>
                    <tfoot id="tfootBulan" style="display:none;">
                        <tr style="font-weight:bold; background-color:#f8f9fa;">
                            <td colspan="3" class="text-right">Jumlah Keseluruhan</td>
                            <td id="footTransaksi">0</td>
                            <td id="footBerat">0</td>
                            <td id="footPendapatan">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let tableBulan = $('#tableBulan').DataTable({
        "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
        "paging": false, "info": false, "searching": false, "ordering": false,
        "columnDefs": [
            { "className": "text-right", "targets": [3, 4, 5] }
        ]
    });

    loadData();

    $('#formFilter').on('submit', function(e) { e.preventDefault(); loadData(); });
    $('#btnReset').on('click', function() { $('#formFilter')[0].reset(); loadData(); });

    $('#btnCetakPdf').on('click', function() {
        let params = new URLSearchParams($('#formFilter').serialize());
        window.open('{{ route("admin.laporan.pertahun.cetak") }}?' + params.toString(), '_blank');
    });

    function loadData() {
        $.ajax({
            url: '{{ route("admin.laporan.pertahun.data") }}',
            method: 'GET',
            data: $('#formFilter').serialize(),
            success: function(response) {
                if (response.success) {
                    $('#statTotalTransaksi').text(response.data.statistik.total_transaksi);
                    $('#statTotalPendapatan').text('Rp ' + formatRupiah(response.data.statistik.total_pendapatan));
                    $('#statTotalBerat').text(parseFloat(response.data.statistik.total_berat || 0).toFixed(2));

                    tableBulan.clear();

                    let totalTrx = 0, totalBerat = 0, totalPendapatan = 0;

                    if (response.data.perBulan.length > 0) {
                        response.data.perBulan.forEach(function(row, i) {
                            totalTrx += row.total_transaksi;
                            totalBerat += parseFloat(row.total_berat || 0);
                            totalPendapatan += parseFloat(row.total_pendapatan || 0);

                            tableBulan.row.add([
                                i + 1,
                                row.tahun,
                                row.bulan,
                                row.total_transaksi,
                                parseFloat(row.total_berat || 0).toFixed(2),
                                'Rp ' + formatRupiah(row.total_pendapatan || 0)
                            ]);
                        });

                        $('#tfootBulan').show();
                        $('#footTransaksi').text(totalTrx);
                        $('#footBerat').text(totalBerat.toFixed(2));
                        $('#footPendapatan').text('Rp ' + formatRupiah(totalPendapatan));
                    } else {
                        $('#tfootBulan').hide();
                    }

                    tableBulan.draw();
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr.status, xhr.responseText);
                Swal.fire({ icon: 'error', title: 'Gagal!', html: 'Gagal memuat data laporan.' });
            }
        });
    }

    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
});
</script>
@endpush
