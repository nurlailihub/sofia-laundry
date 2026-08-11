@extends('layouts.admin')

@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Kelola Booking</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kelola Booking</h3>
            </div>
            <div class="card-body">
                <table id="tableBooking" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4%">No</th>
                            <th>No. Booking</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Tgl Booking</th>
                            <th>Antar/Jemput</th>
                            <th>Metode Bayar</th>
                            <th>DP</th>
                            <th>Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $index => $booking)
                        <tr>
                            <td>{{ $bookings->firstItem() + $index }}</td>

                            {{-- Poin 3: Nomor Booking --}}
                            <td>
                                <span class="font-weight-bold text-primary" style="font-size:.9rem;">
                                    {{ $booking->kode_reservasi ?? 'RSV-' . str_pad($booking->id_booking, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <br><small class="text-muted">#{{ str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) }}</small>
                            </td>

                            <td>
                                <div class="font-weight-bold">{{ $booking->pelanggan->nama_pelanggan ?? '-' }}</div>
                                <small class="text-muted">{{ $booking->pelanggan->no_hp ?? '' }}</small>
                            </td>

                            <td>
                                @if($booking->layanans->count() > 0)
                                    @foreach($booking->layanans as $bl)
                                        <span class="badge badge-light border text-dark d-block mb-1" style="font-size:.78rem; white-space:normal; text-align:left;">
                                            <i class="fas fa-tshirt mr-1 text-primary"></i>
                                            {{ $bl->layanan->nama_layanan ?? '-' }}
                                            @if($bl->estimasi_berat > 0)
                                            <small class="text-muted ml-1">({{ $bl->estimasi_berat }} kg)</small>
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    {{ $booking->layanan->nama_layanan ?? '-' }}
                                @endif
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d/m/Y') }}
                                @if($booking->waktu_booking)
                                <br><small class="text-muted">{{ $booking->waktu_booking }}</small>
                                @endif
                            </td>

                            {{-- Poin 4: Info antar/jemput lebih jelas --}}
                            <td>
                                @php
                                    $tipeLabel = [
                                        'none'     => ['label' => 'Antar Sendiri', 'icon' => 'fas fa-walking', 'color' => 'secondary'],
                                        'pickup'   => ['label' => 'Dijemput Admin', 'icon' => 'fas fa-hand-holding', 'color' => 'info'],
                                        'delivery' => ['label' => 'Diantar Admin', 'icon' => 'fas fa-truck', 'color' => 'primary'],
                                        'both'     => ['label' => 'Jemput & Antar', 'icon' => 'fas fa-exchange-alt', 'color' => 'purple'],
                                    ];
                                    $tipe = $tipeLabel[$booking->tipe_antar_jemput] ?? $tipeLabel['none'];
                                @endphp
                                <span class="badge badge-{{ $tipe['color'] }}">
                                    <i class="{{ $tipe['icon'] }} mr-1"></i>{{ $tipe['label'] }}
                                </span>
                                @if($booking->tipe_antar_jemput !== 'none')
                                    @if($booking->biaya_antar_jemput > 0)
                                    <br><small class="text-success font-weight-bold">Rp {{ number_format($booking->biaya_antar_jemput, 0, ',', '.') }}</small>
                                    @else
                                    <br><small class="text-warning"><i class="fas fa-clock mr-1"></i>Tarif belum ditentukan</small>
                                    @endif
                                    @if($booking->alamat_jemput)
                                    <br><small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($booking->alamat_jemput, 30) }}</small>
                                    @elseif($booking->alamat_antar)
                                    <br><small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($booking->alamat_antar, 30) }}</small>
                                    @endif
                                @endif
                            </td>

                            <td>
                                @php
                                    $metodeIcon  = ['cash'=>'fas fa-money-bill-wave text-success','transfer'=>'fas fa-university text-primary','qris'=>'fas fa-qrcode text-warning'];
                                    $metodeLabel = ['cash'=>'Cash','transfer'=>'Transfer','qris'=>'QRIS'];
                                    $m = $booking->metode_bayar ?? 'cash';
                                @endphp
                                <i class="{{ $metodeIcon[$m] ?? 'fas fa-money-bill' }} mr-1"></i>
                                <small>{{ $metodeLabel[$m] ?? $m }}</small>
                                @if ($booking->bukti_pembayaran)
                                <br>
                                <a href="{{ asset('storage/' . $booking->bukti_pembayaran) }}" target="_blank"
                                   class="badge badge-light border text-primary" style="font-size:.7rem;">
                                    <i class="fas fa-image mr-1"></i>Lihat Bukti
                                </a>
                                @endif
                            </td>

                            {{-- Poin 5: DP status pending --}}
                            <td>
                                @if (($booking->dp_bayar ?? 0) > 0)
                                    <span class="badge badge-success">Rp {{ number_format($booking->dp_bayar, 0, ',', '.') }}</span>
                                    <br><small class="text-muted">Sisa: Rp {{ number_format($booking->sisa_bayar ?? 0, 0, ',', '.') }}</small>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                    <br><small class="text-muted">Belum ada DP</small>
                                @endif
                            </td>

                            <td>
                                @switch($booking->status)
                                    @case('pending') <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>Pending</span> @break
                                    @case('confirmed') <span class="badge badge-primary"><i class="fas fa-check mr-1"></i>Confirmed</span> @break
                                    @case('completed') <span class="badge badge-success"><i class="fas fa-flag-checkered mr-1"></i>Selesai</span> @break
                                    @case('cancelled') <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Batal</span> @break
                                @endswitch
                            </td>

                            <td>
                                @if($booking->status === 'pending')
                                <a href="{{ route('admin.bookings.confirm.form', $booking->id_booking) }}"
                                   class="btn btn-success btn-sm mb-1" title="Konfirmasi & Input Berat Asli">
                                    <i class="fas fa-check"></i> Konfirmasi
                                </a>
                                <button type="button" class="btn btn-info btn-sm mb-1" title="Catat DP"
                                    data-toggle="modal" data-target="#modalDp{{ $booking->id_booking }}">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </button>
                                <form action="{{ route('admin.bookings.cancel', $booking->id_booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm mb-1" title="Batalkan"
                                        onclick="return confirm('Batalkan booking ini?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif

                                @if($booking->status === 'confirmed' && ($booking->sisa_bayar ?? 0) > 0)
                                <button type="button" class="btn btn-info btn-sm mb-1" title="Catat Pembayaran"
                                    data-toggle="modal" data-target="#modalDp{{ $booking->id_booking }}">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </button>
                                @endif

                                <a href="{{ route('admin.bookings.faktur', $booking->id_booking) }}"
                                   class="btn btn-secondary btn-sm mb-1" title="Lihat Faktur">
                                    <i class="fas fa-file-invoice"></i>
                                </a>

                                @if($booking->status !== 'pending')
                                <form action="{{ route('admin.bookings.destroy', $booking->id_booking) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        @if($booking->status === 'pending' || ($booking->status === 'confirmed' && ($booking->sisa_bayar ?? 0) > 0))
                        <div class="modal fade" id="modalDp{{ $booking->id_booking }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h5 class="modal-title text-white">
                                            <i class="fas fa-hand-holding-usd mr-2"></i>
                                            {{ $booking->status === 'pending' ? 'Catat DP Booking' : 'Catat Pembayaran' }}
                                            — {{ $booking->kode_reservasi }}
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                    </div>
                                    <form action="{{ route('admin.bookings.bayar-dp', $booking->id_booking) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            @if($booking->status === 'pending')
                                            <div class="alert alert-warning py-2 mb-3" style="font-size:.82rem;">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Booking masih <strong>Pending</strong>. DP dicatat dulu sebelum dikonfirmasi admin.
                                            </div>
                                            @endif
                                            <p class="text-muted small mb-3">
                                                Sudah DP: <strong>Rp {{ number_format($booking->dp_bayar ?? 0, 0, ',', '.') }}</strong><br>
                                                Sisa Bayar: <strong class="text-danger">Rp {{ number_format($booking->sisa_bayar ?? 0, 0, ',', '.') }}</strong>
                                            </p>
                                            <div class="form-group">
                                                <label>Jumlah Bayar <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                                    <input type="number" name="jumlah_dp" class="form-control" min="1" step="1000" required>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label>Metode Pembayaran <span class="text-danger">*</span></label>
                                                <select name="metode_bayar" class="form-control" required>
                                                    <option value="cash">Cash</option>
                                                    <option value="transfer">Transfer</option>
                                                    <option value="qris">QRIS</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-info">
                                                <i class="fas fa-save mr-1"></i>Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3">{{ $bookings->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#tableBooking').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        paging: false,
        order: [[0, 'desc']],
    });

    $('.form-delete').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Hapus booking ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});
</script>
@endpush
