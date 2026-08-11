@extends('layouts.admin')

@section('title', 'Catat Pembayaran')
@section('page-title', 'Catat Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.transaksis.index') }}">Transaksi</a></li>
<li class="breadcrumb-item active">Catat Pembayaran</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card mb-4">
            <div class="card-header bg-primary">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-receipt mr-2"></i>
                    Tagihan Transaksi #{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="text-muted" width="140">Pelanggan</td>
                                <td><strong>{{ $transaksi->pelanggan->nama_pelanggan ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. HP</td>
                                <td>{{ $transaksi->pelanggan->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Masuk</td>
                                <td>{{ $transaksi->tanggal_masuk->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Berat</td>
                                <td><strong>{{ $transaksi->total_berat }} kg</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            @foreach ($transaksi->detailTransaksi as $d)
                            <tr>
                                <td class="text-muted">{{ $d->layanan->nama_layanan ?? '-' }}</td>
                                <td class="text-right">{{ $d->berat }} kg × Rp {{ number_format($d->layanan->harga_per_kg ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            @php
                                $biayaAntar = $transaksi->biaya_antar;
                                $tipeAntar  = $transaksi->tipe_antar;
                                $tipeLabel  = ['none'=>'Sendiri','pickup'=>'Dijemput','delivery'=>'Diantar','both'=>'Jemput & Antar'];
                            @endphp
                            @if ($biayaAntar > 0)
                            <tr>
                                <td class="text-muted">
                                    <i class="fas fa-truck mr-1 text-info"></i>
                                    Antar/Jemput
                                    <small class="text-muted">({{ $tipeLabel[$tipeAntar] ?? '-' }})</small>
                                </td>
                                <td class="text-right"></td>
                                <td class="text-right">Rp {{ number_format($biayaAntar, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <td colspan="2"><strong>Total Tagihan</strong></td>
                                <td class="text-right">
                                    <strong class="text-primary" style="font-size:1.3rem;">
                                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            @if ($transaksi->pembayaran && $transaksi->pembayaran->status_bayar === 'belum')
                            <tr>
                                <td colspan="2" class="text-success">Sudah Dibayar</td>
                                <td class="text-right text-success">
                                    Rp {{ number_format($transaksi->pembayaran->jumlah_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                            @php $sisaTagihan = $totalTagihan - ($transaksi->pembayaran->jumlah_bayar ?? 0); @endphp
                            <tr>
                                <td colspan="2"><strong class="text-danger">Sisa Tagihan</strong></td>
                                <td class="text-right">
                                    <strong class="text-danger" style="font-size:1.1rem;">
                                        Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-credit-card mr-2"></i>Form Catat Pembayaran</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('admin.pembayarans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_transaksi" value="{{ $transaksi->id_transaksi }}">

                    <div class="form-group">
                        <label class="font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="row g-3 mt-1">
                            <div class="col-md-4">
                                <div class="metode-card border rounded p-3 text-center cursor-pointer {{ old('metode_bayar') === 'cash' ? 'border-primary bg-light' : '' }}" data-metode="cash">
                                    <input type="radio" name="metode_bayar" value="cash" id="m_cash" class="d-none" {{ old('metode_bayar', 'cash') === 'cash' ? 'checked' : '' }}>
                                    <label for="m_cash" style="cursor:pointer;width:100%;">
                                        <i class="fas fa-money-bill-wave fa-2x text-success mb-2 d-block"></i>
                                        <div class="font-weight-bold">Bayar di Tempat</div>
                                        <small class="text-muted">Cash</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metode-card border rounded p-3 text-center cursor-pointer {{ old('metode_bayar') === 'transfer' ? 'border-primary bg-light' : '' }}" data-metode="transfer">
                                    <input type="radio" name="metode_bayar" value="transfer" id="m_transfer" class="d-none" {{ old('metode_bayar') === 'transfer' ? 'checked' : '' }}>
                                    <label for="m_transfer" style="cursor:pointer;width:100%;">
                                        <i class="fas fa-university fa-2x text-primary mb-2 d-block"></i>
                                        <div class="font-weight-bold">Transfer Bank</div>
                                        <small class="text-muted">BCA / Mandiri / BRI</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metode-card border rounded p-3 text-center cursor-pointer {{ old('metode_bayar') === 'qris' ? 'border-primary bg-light' : '' }}" data-metode="qris">
                                    <input type="radio" name="metode_bayar" value="qris" id="m_qris" class="d-none" {{ old('metode_bayar') === 'qris' ? 'checked' : '' }}>
                                    <label for="m_qris" style="cursor:pointer;width:100%;">
                                        <i class="fas fa-qrcode fa-2x text-warning mb-2 d-block"></i>
                                        <div class="font-weight-bold">QRIS</div>
                                        <small class="text-muted">Scan QR Code</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Jumlah Dibayar <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    @php
                                        $sudahBayar = $transaksi->pembayaran?->status_bayar === 'belum' ? ($transaksi->pembayaran->jumlah_bayar ?? 0) : 0;
                                        $sisaTagihan = $totalTagihan - $sudahBayar;
                                    @endphp
                                    <input type="number" name="jumlah_bayar" id="jumlahBayar"
                                        class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                        value="{{ old('jumlah_bayar', $sisaTagihan) }}"
                                        min="0" step="1" required>
                                    @error('jumlah_bayar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <small class="text-muted">Tagihan: <strong>Rp {{ number_format($totalTagihan, 0, ',', '.') }}</strong>
                                    @if ($sudahBayar > 0)
                                        | Sudah dibayar: <strong class="text-success">Rp {{ number_format($sudahBayar, 0, ',', '.') }}</strong>
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kembalian</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" id="kembalian" class="form-control bg-light" readonly
                                        value="{{ number_format(0, 0, ',', '.') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="wrapNomorRef" style="{{ old('metode_bayar','cash') !== 'cash' ? '' : 'display:none;' }}">
                        <label class="font-weight-bold">Nomor Referensi / Bukti Transfer</label>
                        <input type="text" name="nomor_referensi" class="form-control"
                            value="{{ old('nomor_referensi') }}"
                            placeholder="Contoh: TRF-123456 atau nomor struk QRIS">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"
                            placeholder="Catatan pembayaran (opsional)">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <a href="{{ route('admin.transaksis.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-check mr-2"></i>Simpan & Cetak Faktur
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.metode-card { transition: all .2s; cursor: pointer; }
.metode-card:hover { border-color: #007bff !important; background: #f0f7ff; }
.metode-card.selected { border-color: #007bff !important; background: #e8f0fe; }
</style>
@endpush

@push('scripts')
<script>
const tagihan = {{ $sisaTagihan }};

document.querySelectorAll('.metode-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.metode-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        const metode = this.dataset.metode;
        document.getElementById('m_' + metode).checked = true;
        document.getElementById('wrapNomorRef').style.display = metode !== 'cash' ? 'block' : 'none';
    });
});

document.querySelectorAll('.metode-card').forEach(card => {
    if (document.getElementById('m_' + card.dataset.metode)?.checked) {
        card.classList.add('selected');
    }
});

document.getElementById('jumlahBayar').addEventListener('input', function () {
    const bayar     = parseFloat(this.value) || 0;
    const kembalian = bayar - tagihan;
    const el        = document.getElementById('kembalian');
    el.value        = kembalian >= 0
        ? new Intl.NumberFormat('id-ID').format(kembalian)
        : '0';
    el.classList.toggle('text-danger', kembalian < 0);
    el.classList.toggle('text-success', kembalian >= 0);
});

document.getElementById('jumlahBayar').dispatchEvent(new Event('input'));
</script>
@endpush
