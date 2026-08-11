@extends('layouts.customer')

@section('title', 'Booking Layanan')
@section('page-title', 'Booking Layanan')

@push('styles')
<style>
.metode-card { transition: all .2s; cursor: pointer; border: 2px solid #e5e7eb; border-radius: 12px; }
.metode-card:has(input:checked) { border-color: #2563eb; background: #eff6ff; }
.info-box { display: none; border-radius: 12px; padding: 1rem 1.25rem; margin-top: .75rem; }
.info-box.show { display: block; }
.total-box { background: linear-gradient(135deg, #1e3a8a, #2563eb); color: white; border-radius: 14px; padding: 1.25rem 1.5rem; }
.upload-area { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all .2s; }
.upload-area:hover { border-color: #2563eb; background: #eff6ff; }
.upload-area.has-file { border-color: #10b981; background: #f0fdf4; }
</style>
@endpush

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">

                @if ($errors->any())
                <div class="alert alert-danger rounded-3 mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('customer.booking.store') }}" method="POST" enctype="multipart/form-data" id="formBooking" novalidate>
                    @csrf

                    <div class="p-3 rounded-3 mb-4" style="background:#eff6ff;">
                        <h6 class="fw-bold mb-0 text-primary">
                            <i class="fas fa-user me-2"></i>Booking atas nama: {{ $pelanggan->nama_pelanggan ?? auth()->user()->nama_user }}
                        </h6>
                        <small class="text-muted">{{ $pelanggan->no_hp ?? '' }} · {{ $pelanggan->alamat ?? '' }}</small>
                    </div>

                    <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;">
                        <h6 class="fw-semibold mb-3 text-muted" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Detail Layanan</h6>

                        <div id="layananContainer">
                            {{-- Row pertama --}}
                            <div class="layanan-row border rounded-3 p-3 mb-2 position-relative" data-index="0">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small">Jenis Layanan <span class="text-danger">*</span></label>
                                        <select name="layanans[0][id_layanan]" class="form-select layanan-select" required data-index="0">
                                            <option value="">— Pilih Layanan —</option>
                                            @foreach ($layanans as $layanan)
                                            <option value="{{ $layanan->id_layanan }}"
                                                data-harga="{{ $layanan->harga_per_kg }}"
                                                {{ old('layanans.0.id_layanan') == $layanan->id_layanan ? 'selected' : '' }}>
                                                {{ $layanan->nama_layanan }} — Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}/kg
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small">Estimasi Berat (kg)</label>
                                        <input type="number" name="layanans[0][estimasi_berat]"
                                            class="form-control berat-input" data-index="0"
                                            value="{{ old('layanans.0.estimasi_berat') }}"
                                            placeholder="Contoh: 3.5" min="0.1" step="0.5">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold small">Subtotal</label>
                                        <div class="subtotal-text fw-bold text-primary small pt-1" data-index="0">Rp 0</div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row position-absolute"
                                    style="top:8px;right:8px;display:none;" onclick="removeRow(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="addLayananRow()">
                            <i class="fas fa-plus me-1"></i>Tambah Layanan Lain
                        </button>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Tanggal Booking <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_booking"
                                    class="form-control @error('tanggal_booking') is-invalid @enderror"
                                    value="{{ old('tanggal_booking') }}" min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_booking')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Waktu</label>
                                <input type="time" name="waktu_booking" class="form-control" value="{{ old('waktu_booking') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan khusus (opsional)">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;">
                        <h6 class="fw-semibold mb-3 text-muted" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Opsi Antar / Jemput</h6>
                        <div class="row g-2 mb-3">
                            @php
                                $tipe = old('tipe_antar_jemput', 'none');
                                $tarifs = \App\Models\TarifAntarJemput::all()->keyBy('tipe');
                                $hargaPickup   = (int)($tarifs['pickup']->harga   ?? 0);
                                $hargaDelivery = (int)($tarifs['delivery']->harga ?? 0);
                                $hargaBoth     = (int)($tarifs['both']->harga     ?? 0);
                            @endphp
                            <div class="col-6 col-md-3">
                                <input type="radio" class="btn-check tipe-radio" name="tipe_antar_jemput" id="t_none" value="none" data-biaya="0" {{ $tipe === 'none' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary w-100 text-center py-3" for="t_none" style="font-size:.8rem;">
                                    <i class="fas fa-walking d-block mb-1 fs-5"></i>Antar Sendiri<br><small class="text-success fw-bold">Gratis</small>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <input type="radio" class="btn-check tipe-radio" name="tipe_antar_jemput" id="t_pickup" value="pickup" data-biaya="{{ $hargaPickup }}" {{ $tipe === 'pickup' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary w-100 text-center py-3" for="t_pickup" style="font-size:.8rem;">
                                    <i class="fas fa-hand-holding d-block mb-1 fs-5"></i>Dijemput<br>
                                    <small class="fw-bold text-primary">Rp {{ number_format($hargaPickup, 0, ',', '.') }}</small>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <input type="radio" class="btn-check tipe-radio" name="tipe_antar_jemput" id="t_delivery" value="delivery" data-biaya="{{ $hargaDelivery }}" {{ $tipe === 'delivery' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary w-100 text-center py-3" for="t_delivery" style="font-size:.8rem;">
                                    <i class="fas fa-truck d-block mb-1 fs-5"></i>Diantar<br>
                                    <small class="fw-bold text-primary">Rp {{ number_format($hargaDelivery, 0, ',', '.') }}</small>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <input type="radio" class="btn-check tipe-radio" name="tipe_antar_jemput" id="t_both" value="both" data-biaya="{{ $hargaBoth }}" {{ $tipe === 'both' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary w-100 text-center py-3" for="t_both" style="font-size:.8rem;">
                                    <i class="fas fa-exchange-alt d-block mb-1 fs-5"></i>Jemput & Antar<br>
                                    <small class="fw-bold text-primary">Rp {{ number_format($hargaBoth, 0, ',', '.') }}</small>
                                </label>
                            </div>
                        </div>

                        <div id="fieldJemput" style="display:none;" class="mb-3">
                            <label class="form-label fw-semibold small">Alamat Penjemputan <span class="text-danger">*</span></label>
                            <textarea name="alamat_jemput" class="form-control @error('alamat_jemput') is-invalid @enderror" rows="2"
                                placeholder="Alamat lengkap penjemputan">{{ old('alamat_jemput') }}</textarea>
                            @error('alamat_jemput')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div id="fieldAntar" style="display:none;">
                            <label class="form-label fw-semibold small">Alamat Pengantaran <span class="text-danger">*</span></label>
                            <textarea name="alamat_antar" class="form-control @error('alamat_antar') is-invalid @enderror" rows="2"
                                placeholder="Alamat lengkap pengantaran">{{ old('alamat_antar') }}</textarea>
                            @error('alamat_antar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="total-box mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-size:.85rem;opacity:.8;">Estimasi Layanan</span>
                            <span id="totalLayanan">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1" id="rowBiayaAntar" style="display:none!important;">
                            <span style="font-size:.85rem;opacity:.8;"><i class="fas fa-truck me-1"></i>Biaya Antar/Jemput</span>
                            <span id="totalBiayaAntar">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top:1px solid rgba(255,255,255,.25);">
                            <span class="fw-bold">Estimasi Total</span>
                            <span class="fw-bold" style="font-size:1.2rem;" id="totalEstimasi">Rp 0</span>
                        </div>
                        <div class="mt-1" style="font-size:.72rem;opacity:.65;">
                            * Estimasi berdasarkan berat layanan. Tarif antar/jemput dapat disesuaikan admin sesuai jarak.
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;">
                        <h6 class="fw-semibold mb-3 text-muted" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">
                            <i class="fas fa-hand-holding-usd me-1"></i>Bayar DP (Opsional)
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Jumlah DP</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="dp_bayar" id="inputDpBayar"
                                        class="form-control"
                                        value="{{ old('dp_bayar', 0) }}" min="0" step="1000" placeholder="0">
                                </div>
                                <small class="text-muted">Kosongkan atau isi 0 jika tidak bayar DP</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Sisa Bayar</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="text" id="sisaBayar" class="form-control bg-light" readonly value="Rp 0">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 p-2 rounded-2 small" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;">
                            <i class="fas fa-info-circle me-1"></i>
                            DP membantu mengamankan booking Anda. Sisa pembayaran dilakukan saat pengambilan laundry.
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-4" style="background:#f8fafc;">
                        <h6 class="fw-semibold mb-3 text-muted" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">
                            <i class="fas fa-credit-card me-1"></i>Metode Pembayaran <span class="text-danger">*</span>
                        </h6>
                        <div class="row g-2">
                            @php $metode = old('metode_bayar', 'cash'); @endphp
                            <div class="col-md-4">
                                <div class="metode-card p-3 text-center">
                                    <input type="radio" class="btn-check metode-radio" name="metode_bayar" id="mb_cash" value="cash" {{ $metode === 'cash' ? 'checked' : '' }}>
                                    <label for="mb_cash" style="cursor:pointer;display:block;">
                                        <i class="fas fa-money-bill-wave text-success d-block mb-1" style="font-size:1.5rem;"></i>
                                        <span class="fw-bold d-block small">Bayar di Tempat</span>
                                        <span class="text-muted d-block" style="font-size:.75rem;">Cash saat ambil</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metode-card p-3 text-center">
                                    <input type="radio" class="btn-check metode-radio" name="metode_bayar" id="mb_transfer" value="transfer" {{ $metode === 'transfer' ? 'checked' : '' }}>
                                    <label for="mb_transfer" style="cursor:pointer;display:block;">
                                        <i class="fas fa-university text-primary d-block mb-1" style="font-size:1.5rem;"></i>
                                        <span class="fw-bold d-block small">Transfer Bank</span>
                                        <span class="text-muted d-block" style="font-size:.75rem;">BCA / Mandiri / BRI</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metode-card p-3 text-center">
                                    <input type="radio" class="btn-check metode-radio" name="metode_bayar" id="mb_qris" value="qris" {{ $metode === 'qris' ? 'checked' : '' }}>
                                    <label for="mb_qris" style="cursor:pointer;display:block;">
                                        <i class="fas fa-qrcode text-warning d-block mb-1" style="font-size:1.5rem;"></i>
                                        <span class="fw-bold d-block small">QRIS</span>
                                        <span class="text-muted d-block" style="font-size:.75rem;">Scan QR Code</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('metode_bayar')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror

                        <div id="infoTransfer" class="info-box {{ $metode === 'transfer' ? 'show' : '' }}" style="background:#eff6ff;border:1px solid #bfdbfe;">
                            <div class="fw-bold mb-2 text-primary"><i class="fas fa-university me-2"></i>Informasi Rekening Transfer</div>
                            <table style="font-size:.88rem;width:100%;">
                                <tr>
                                    <td style="width:100px;color:#6b7280;padding:.2rem 0;">Bank</td>
                                    <td class="fw-semibold">BCA</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;padding:.2rem 0;">No. Rekening</td>
                                    <td class="fw-semibold" id="noRek">1234 5678 90</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;padding:.2rem 0;">Atas Nama</td>
                                    <td class="fw-semibold">Sofia Laundry</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;padding:.2rem 0;">Jumlah</td>
                                    <td class="fw-bold text-primary" id="transferJumlah">Rp 0</td>
                                </tr>
                            </table>
                            <div class="mt-2 p-2 rounded-2 text-warning small" style="background:#fef3c7;">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Harap transfer tepat sesuai total estimasi dan upload bukti transfer di bawah.
                            </div>
                        </div>

                        <div id="infoQris" class="info-box {{ $metode === 'qris' ? 'show' : '' }}" style="background:#fffbeb;border:1px solid #fde68a;">
                            <div class="fw-bold mb-2 text-warning"><i class="fas fa-qrcode me-2"></i>Scan QRIS untuk Pembayaran</div>
                            <div class="text-center py-2">
                                <div style="width:160px;height:160px;background:#f3f4f6;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;border:2px solid #e5e7eb;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-qrcode" style="font-size:3rem;opacity:.3;"></i>
                                        <div class="small mt-1" style="font-size:.7rem;">QR Code<br>Sofia Laundry</div>
                                    </div>
                                </div>
                                <div class="mt-2 small text-muted">Simpan gambar QRIS ini & scan menggunakan aplikasi dompet digital</div>
                                <div class="mt-1 fw-bold text-warning" id="qrisJumlah" style="font-size:.9rem;">Total: Rp 0</div>
                            </div>
                            <div class="mt-2 p-2 rounded-2 text-warning small" style="background:#fef3c7;">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Setelah transfer, upload bukti pembayaran di bawah agar admin dapat memverifikasi.
                            </div>
                        </div>
                    </div>

                    <div id="sectionBukti" class="p-3 rounded-3 mb-4 {{ in_array($metode, ['transfer','qris']) ? '' : 'd-none' }}" style="background:#f8fafc;">
                        <h6 class="fw-semibold mb-3 text-muted" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">
                            <i class="fas fa-camera me-1"></i>Upload Bukti Pembayaran
                            @if(in_array($metode, ['transfer','qris']))
                            <span class="text-danger">*</span>
                            @endif
                        </h6>

                        <div class="upload-area" id="uploadArea" onclick="document.getElementById('inputBukti').click()">
                            <div id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt mb-2 text-muted" style="font-size:2rem;"></i>
                                <div class="small fw-semibold text-muted">Klik untuk pilih gambar</div>
                                <div style="font-size:.75rem;color:#9ca3af;">JPG, PNG, WEBP — Maks 2 MB</div>
                            </div>
                            <div id="uploadPreview" style="display:none;">
                                <img id="previewImg" src="" alt="Preview" style="max-height:180px;border-radius:8px;max-width:100%;">
                                <div class="small text-success mt-2 fw-semibold" id="uploadFileName"></div>
                            </div>
                        </div>
                        <input type="file" name="bukti_pembayaran" id="inputBukti" accept="image/*" style="display:none;">
                        @error('bukti_pembayaran')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" id="btnSubmit" class="btn btn-primary w-100 py-3 fw-bold" style="font-size:1rem;">
                        <i class="fas fa-calendar-check me-2"></i>Kirim Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const layanans  = @json($layanansJson);
let rowCount    = 1;

function fmt(n) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(n));
}

// ===== Multi-Layanan =====
function addLayananRow() {
    const idx = rowCount++;
    const options = layanans.map(l =>
        `<option value="${l.id}" data-harga="${l.harga_per_kg}">
            ${l.nama} — Rp ${new Intl.NumberFormat('id-ID').format(l.harga_per_kg)}/kg
        </option>`
    ).join('');

    const html = `
    <div class="layanan-row border rounded-3 p-3 mb-2 position-relative" data-index="${idx}">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Jenis Layanan <span class="text-danger">*</span></label>
                <select name="layanans[${idx}][id_layanan]" class="form-select layanan-select" required data-index="${idx}">
                    <option value="">— Pilih Layanan —</option>
                    ${options}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Estimasi Berat (kg)</label>
                <input type="number" name="layanans[${idx}][estimasi_berat]"
                    class="form-control berat-input" data-index="${idx}"
                    placeholder="Contoh: 3.5" min="0.1" step="0.5">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Subtotal</label>
                <div class="subtotal-text fw-bold text-primary small pt-1" data-index="${idx}">Rp 0</div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row position-absolute"
            style="top:8px;right:8px;" onclick="removeRow(this)">
            <i class="fas fa-times"></i>
        </button>
    </div>`;

    document.getElementById('layananContainer').insertAdjacentHTML('beforeend', html);
    updateRemoveButtons();
    bindRowEvents(idx);
}

function removeRow(btn) {
    btn.closest('.layanan-row').remove();
    updateRemoveButtons();
    hitungTotal();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.layanan-row');
    rows.forEach(row => {
        const btn = row.querySelector('.btn-remove-row');
        if (btn) btn.style.display = rows.length > 1 ? 'block' : 'none';
    });
}

function bindRowEvents(idx) {
    document.querySelector(`[name="layanans[${idx}][id_layanan]"]`)
        ?.addEventListener('change', hitungTotal);
    document.querySelector(`[name="layanans[${idx}][estimasi_berat]"]`)
        ?.addEventListener('input', hitungTotal);
}

bindRowEvents(0);

function hitungTotal() {
    let totalLayananHarga = 0;

    document.querySelectorAll('.layanan-row').forEach(row => {
        const select     = row.querySelector('.layanan-select');
        const beratEl    = row.querySelector('.berat-input');
        const subtotalEl = row.querySelector('.subtotal-text');

        const layananId = select?.value;
        const berat     = parseFloat(beratEl?.value) || 0;
        const layanan   = layanans.find(l => l.id == layananId);
        const harga     = layanan ? layanan.harga_per_kg : 0;
        const subtotal  = harga * berat;

        if (subtotalEl) subtotalEl.textContent = fmt(subtotal);
        totalLayananHarga += subtotal;
    });

    const tipeEl  = document.querySelector('.tipe-radio:checked');
    const biayaAJ = tipeEl ? parseFloat(tipeEl.dataset.biaya || 0) : 0;
    const total   = totalLayananHarga + biayaAJ;

    document.getElementById('totalLayanan').textContent   = fmt(totalLayananHarga);
    document.getElementById('totalEstimasi').textContent  = fmt(total);
    document.getElementById('transferJumlah').textContent = fmt(total);
    document.getElementById('qrisJumlah').textContent     = 'Total: ' + fmt(total);

    const rowBiaya = document.getElementById('rowBiayaAntar');
    if (biayaAJ > 0) {
        document.getElementById('totalBiayaAntar').textContent = fmt(biayaAJ);
        rowBiaya.style.display = 'flex';
    } else {
        rowBiaya.style.display = 'none';
    }

    const dpBayar = parseFloat(document.getElementById('inputDpBayar').value) || 0;
    document.getElementById('sisaBayar').value = fmt(Math.max(0, total - dpBayar));
}

function toggleMetode() {
    const v = document.querySelector('.metode-radio:checked')?.value || 'cash';
    document.getElementById('infoTransfer').classList.toggle('show', v === 'transfer');
    document.getElementById('infoQris').classList.toggle('show', v === 'qris');
    document.getElementById('sectionBukti').classList.toggle('d-none', v === 'cash');
}

function toggleTipe() {
    const v = document.querySelector('.tipe-radio:checked')?.value || 'none';
    document.getElementById('fieldJemput').style.display = ['pickup','both'].includes(v) ? 'block' : 'none';
    document.getElementById('fieldAntar').style.display  = ['delivery','both'].includes(v) ? 'block' : 'none';
    hitungTotal();
}

document.querySelectorAll('.metode-radio').forEach(r => r.addEventListener('change', toggleMetode));
document.querySelectorAll('.tipe-radio').forEach(r => r.addEventListener('change', toggleTipe));
document.getElementById('inputDpBayar').addEventListener('input', hitungTotal);

document.getElementById('inputBukti').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('uploadFileName').textContent = file.name;
        document.getElementById('uploadPlaceholder').style.display = 'none';
        document.getElementById('uploadPreview').style.display = 'block';
        document.getElementById('uploadArea').classList.add('has-file');
    };
    reader.readAsDataURL(file);
});

document.getElementById('formBooking').addEventListener('submit', function(e) {
    e.preventDefault();

    const errors = [];
    const rows = document.querySelectorAll('.layanan-row');
    rows.forEach((row, i) => {
        const select = row.querySelector('.layanan-select');
        if (!select || !select.value) {
            errors.push(`Baris layanan ${i + 1}: pilih jenis layanan.`);
            select && select.classList.add('is-invalid');
        } else {
            select && select.classList.remove('is-invalid');
        }
    });

    const tgl = document.querySelector('[name="tanggal_booking"]');
    if (!tgl || !tgl.value) {
        errors.push('Tanggal booking wajib diisi.');
        tgl && tgl.classList.add('is-invalid');
    } else {
        tgl && tgl.classList.remove('is-invalid');
    }

    const metode = document.querySelector('.metode-radio:checked');
    if (!metode) errors.push('Pilih metode pembayaran.');

    const tipe = document.querySelector('.tipe-radio:checked')?.value || 'none';
    if (['pickup','both'].includes(tipe)) {
        const aj = document.querySelector('[name="alamat_jemput"]');
        if (!aj || !aj.value.trim()) {
            errors.push('Alamat penjemputan wajib diisi.');
            aj && aj.classList.add('is-invalid');
        }
    }
    if (['delivery','both'].includes(tipe)) {
        const aa = document.querySelector('[name="alamat_antar"]');
        if (!aa || !aa.value.trim()) {
            errors.push('Alamat pengantaran wajib diisi.');
            aa && aa.classList.add('is-invalid');
        }
    }

    if (errors.length > 0) {
        let errBox = document.getElementById('jsErrorBox');
        if (!errBox) {
            errBox = document.createElement('div');
            errBox.id = 'jsErrorBox';
            errBox.className = 'alert alert-danger rounded-3 mb-4';
            document.getElementById('formBooking').prepend(errBox);
        }
        errBox.innerHTML = '<ul class="mb-0 ps-3">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
        errBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
    }

    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim Booking...';
    this.submit();
});

toggleTipe();
toggleMetode();
hitungTotal();
</script>
@endpush
