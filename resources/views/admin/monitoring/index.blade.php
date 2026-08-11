@extends('layouts.admin')

@section('title', 'Update Status Cucian')
@section('page-title', 'Update Status Cucian')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active">Update Status Cucian</li>
@endsection

@push('styles')
<style>
.status-col { min-width: 200px; }
.kanban-card {
    background: white;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,.08);
    border-left: 4px solid;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s;
}
.kanban-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.12); }
.kanban-col {
    background: #f8fafc;
    border-radius: 12px;
    padding: 12px;
    min-height: 200px;
}
.badge-count {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 50%;
    font-size: .7rem; font-weight: 700;
}
</style>
@endpush

@section('content')

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <p class="text-muted mb-0 small">Menampilkan transaksi aktif (belum selesai diambil). Klik kartu untuk update status.</p>
        <button onclick="location.reload()" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-sync me-1"></i>Refresh
        </button>
    </div>
</div>

<div class="d-flex gap-3 overflow-auto pb-3">
    @foreach ($statusList as $key => $label)
    @php
        $items = $grouped->get($key, collect());
        $icon  = $statusIcons[$key];
        $color = $statusColors[$key];
        $borderColors = [
            'secondary'=>'#6b7280','info'=>'#06b6d4','primary'=>'#2563eb',
            'warning'=>'#f59e0b','danger'=>'#ef4444','purple'=>'#7c3aed',
            'success'=>'#10b981','dark'=>'#1f2937'
        ];
        $bc = $borderColors[$color] ?? '#6b7280';
    @endphp
    <div class="status-col flex-shrink-0">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="{{ $icon }} text-{{ $color }}"></i>
            <span class="fw-bold small">{{ $label }}</span>
            <span class="badge-count bg-{{ $color }} text-white ms-auto">{{ $items->count() }}</span>
        </div>
        <div class="kanban-col">
            @forelse ($items as $trx)
            <div class="kanban-card" style="border-left-color:{{ $bc }};"
                onclick="openUpdateModal({{ $trx->id_transaksi }}, '{{ $key }}', '{{ addslashes($trx->catatan_status ?? '') }}')">
                <div class="fw-bold small mb-1">#{{ str_pad($trx->id_transaksi, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="small text-muted mb-1">{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</div>
                <div class="small text-muted">
                    <i class="fas fa-weight me-1"></i>{{ $trx->total_berat }} kg ·
                    <i class="fas fa-calendar me-1"></i>{{ $trx->tanggal_masuk->format('d/m') }}
                    @if ($trx->pelanggan && $trx->pelanggan->no_hp)
                    · <i class="fab fa-whatsapp text-success me-1"></i>
                    @endif
                </div>
                @if ($trx->catatan_status)
                <div class="small text-primary mt-1"><i class="fas fa-comment me-1"></i>{{ Str::limit($trx->catatan_status, 40) }}</div>
                @endif
            </div>
            @empty
            <div class="text-center text-muted py-4" style="font-size:.8rem;">
                <i class="fas fa-inbox opacity-25 d-block mb-1 fs-5"></i>Kosong
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0"><i class="fas fa-list me-2 text-primary"></i>Semua Transaksi Aktif</h5>
    </div>
    <div class="card-body">
        <table id="tableMonitoring" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Berat</th>
                    <th>Tgl Masuk</th>
                    <th>Status Detail</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksis as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>#{{ str_pad($trx->id_transaksi, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $trx->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>
                        @foreach ($trx->detailTransaksi as $d)
                        <span class="badge badge-secondary">{{ $d->layanan->nama_layanan ?? '-' }}</span>
                        @endforeach
                    </td>
                    <td>{{ $trx->total_berat }} kg</td>
                    <td>{{ $trx->tanggal_masuk->format('d/m/Y') }}</td>
                    <td>
                        @php $c = $statusColors[$trx->status_detail] ?? 'secondary'; @endphp
                        <span class="badge badge-{{ $c }}">
                            <i class="{{ $statusIcons[$trx->status_detail] ?? 'fas fa-circle' }} me-1"></i>
                            {{ $statusList[$trx->status_detail] ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $trx->catatan_status ?? '-' }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm"
                            onclick="openUpdateModal({{ $trx->id_transaksi }}, '{{ $trx->status_detail }}', '{{ addslashes($trx->catatan_status ?? '') }}')">
                            <i class="fas fa-edit"></i> Update
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalUpdate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"><i class="fas fa-edit me-2"></i>Update Status Laundry</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="trx_id">
                <input type="hidden" id="trx_pelanggan">

                <div id="infoPelanggan" class="alert alert-info py-2 px-3 mb-3 d-none">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle fa-lg"></i>
                        <div>
                            <strong id="namaPelanggan"></strong><br>
                            <small id="noHpPelanggan" class="text-muted"></small>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="font-weight-bold">
                        <i class="fas fa-tags me-1 text-primary"></i>Status Laundry
                    </label>
                    <select id="new_status" class="form-control">
                        @foreach ($statusList as $key => $label)
                        <option value="{{ $key }}">
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">
                        <i class="fas fa-comment me-1 text-primary"></i>Catatan <small class="text-muted">(opsional)</small>
                    </label>
                    <textarea id="catatan_status" class="form-control" rows="2"
                        placeholder="Contoh: Baju sudah selesai dicuci, menunggu dikeringkan..."></textarea>
                </div>
                <div class="form-group mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="kirim_wa" checked>
                        <label class="custom-control-label font-weight-bold" for="kirim_wa">
                            <i class="fab fa-whatsapp text-success me-1"></i>Kirim Notifikasi WhatsApp ke Pelanggan
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitUpdate()">
                    <i class="fas fa-paper-plane me-1"></i>Update & Kirim
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#tableMonitoring').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        order: [[5, 'asc']],
    });
});

function openUpdateModal(id, currentStatus, catatan) {
    $('#trx_id').val(id);

    const card = $(`.kanban-card[onclick*="openUpdateModal(${id},"]`).first();
    if (card.length) {
        const nama = card.find('.text-muted.mb-1').first().text().trim();
        const waIcon = card.find('.fa-whatsapp');
        if (nama) {
            $('#namaPelanggan').text(nama);
            $('#infoPelanggan').removeClass('d-none');
        }
        if (waIcon.length) {
            $('#noHpPelanggan').html('<i class="fab fa-whatsapp text-success me-1"></i>Nomor WhatsApp tersedia');
        } else {
            $('#noHpPelanggan').text('Nomor WhatsApp tidak tersedia');
        }
    } else {
        $('#infoPelanggan').addClass('d-none');
    }

    $('#new_status').val(currentStatus);
    $('#catatan_status').val(catatan);
    $('#kirim_wa').prop('checked', true);
    $('#modalUpdate').modal('show');
}

function submitUpdate() {
    const id      = $('#trx_id').val();
    const status  = $('#new_status').val();
    const catatan = $('#catatan_status').val();
    const kirimWa = $('#kirim_wa').is(':checked');

    const btn = $('#modalUpdate .modal-footer .btn-primary');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');

    $.ajax({
        url: '/admin/monitoring/' + id + '/status',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status_detail: status,
            catatan_status: catatan,
            kirim_wa: kirimWa ? 1 : 0,
        },
        success: function (res) {
            $('#modalUpdate').modal('hide');
            let icon = 'success';
            let title = 'Status Diperbarui!';
            let html = '<strong>' + res.label + '</strong>';
            if (kirimWa) {
                if (res.notif && res.notif.success) {
                    html += '<br><span class="text-success"><i class="fab fa-whatsapp me-1"></i>Notifikasi WhatsApp berhasil dikirim</span>';
                } else if (res.notif) {
                    html += '<br><span class="text-warning"><i class="fab fa-whatsapp me-1"></i>Notifikasi WhatsApp: ' + (res.notif.message || 'Gagal dikirim') + '</span>';
                }
            }
            Swal.fire({
                icon: icon,
                title: title,
                html: html,
                timer: 2500,
                showConfirmButton: false,
            }).then(() => location.reload());
        },
        error: function (xhr) {
            let msg = 'Terjadi kesalahan saat menyimpan status.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.status === 419) {
                msg = 'Sesi expired. Silakan refresh halaman.';
            } else if (xhr.status === 500) {
                msg = 'Server error (500). Cek log Laravel untuk detail.';
                console.error('Server error:', xhr.responseText);
            } else if (xhr.status === 422) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors;
                if (errors) msg = Object.values(errors).flat().join('<br>');
            }
            Swal.fire('Gagal', msg, 'error');
        }
    }).always(function () {
        btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i>Update & Kirim');
    });
}
</script>
@endpush
