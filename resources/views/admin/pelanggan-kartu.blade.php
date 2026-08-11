@extends('layouts.admin')

@section('title', 'Kartu Member Pelanggan')
@section('page-title', 'Kartu Member Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.pelanggans.index') }}">Kelola Pelanggan</a></li>
<li class="breadcrumb-item active">Kartu Member</li>
@endsection

@push('styles')
<style>
/* ── Layout halaman ── */
.kartu-page {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 0 30px;
}

/* ── Alert sukses custom ── */
.alert-member {
    width: 100%;
    max-width: 760px;
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-radius: 12px;
    padding: 14px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: .9rem;
    color: #166534;
}

/* ── Tombol aksi ── */
.kartu-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 28px;
    flex-wrap: wrap;
    justify-content: center;
}

/* ── Preview kartu di layar ── */
.kartu-preview-wrap {
    display: flex;
    flex-direction: column;
    gap: 16px;
    align-items: center;
    width: 100%;
    max-width: 420px;
}

/* ── Kartu Depan ── */
.kartu-depan {
    width: 400px;
    height: 230px;
    border-radius: 18px;
    background: linear-gradient(135deg, #005F73 0%, #0a9396 55%, #2BB1B1 100%);
    color: white;
    padding: 24px 26px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(0,95,115,.4), 0 4px 12px rgba(0,0,0,.15);
}
.kartu-depan::before {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
    top: -70px; right: -50px;
}
.kartu-depan::after {
    content: '';
    position: absolute;
    width: 140px; height: 140px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
    bottom: -40px; left: -25px;
}
.kd-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.kd-logo-icon {
    width: 32px; height: 32px;
    background: rgba(255,255,255,.2);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
}
.kd-brand { font-size: 14px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
.kd-sub   { font-size: 8.5px; opacity: .6; letter-spacing: 1px; text-transform: uppercase; margin-top: 1px; }
.kd-nama  {
    font-size: 22px; font-weight: 800;
    letter-spacing: .3px; margin-top: 18px; margin-bottom: 3px;
    position: relative; z-index: 1;
}
.kd-hp    { font-size: 12px; opacity: .8; position: relative; z-index: 1; margin-bottom: 20px; }
.kd-bottom {
    display: flex; justify-content: space-between; align-items: flex-end;
    position: relative; z-index: 1;
}
.kd-info-label { font-size: 8px; opacity: .6; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; }
.kd-info-value { font-size: 12px; font-weight: 700; }
.kd-badge {
    background: rgba(255,255,255,.18);
    border: 1.5px solid rgba(255,255,255,.4);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 10px; font-weight: 800;
    letter-spacing: 2px;
}

/* ── Kartu Belakang ── */
.kartu-belakang {
    width: 400px;
    border-radius: 18px;
    background: linear-gradient(160deg, #0f172a 0%, #1e293b 100%);
    color: #e2e8f0;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(0,0,0,.3);
}
.kb-head {
    padding: 16px 22px 12px;
    border-bottom: 1px solid #1e293b;
    display: flex; align-items: center; gap: 8px;
}
.kb-head-icon {
    width: 28px; height: 28px;
    background: #1e3a5f;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
}
.kb-head-title { font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: #94a3b8; }

.kb-body { padding: 16px 22px 20px; }
.kb-field { margin-bottom: 14px; }
.kb-field:last-child { margin-bottom: 0; }
.kb-field-label {
    font-size: 9px; color: #64748b;
    text-transform: uppercase; letter-spacing: 1px;
    margin-bottom: 6px;
    font-family: 'Courier New', monospace;
}
.kb-field-box {
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 10px;
    padding: 10px 14px;
    display: flex; justify-content: space-between; align-items: center;
}
.kb-field-val {
    font-family: 'Courier New', monospace;
    font-size: 16px; font-weight: 700;
    color: #f8fafc;
    letter-spacing: 1.5px;
}
.kb-field-tag {
    font-size: 8.5px; color: #475569;
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 4px;
    padding: 2px 7px;
    font-family: 'Courier New', monospace;
    letter-spacing: .5px;
}
.kb-url {
    margin-top: 16px;
    padding: 10px 14px;
    background: #0f172a;
    border-radius: 8px;
    display: flex; align-items: center; gap: 8px;
    font-size: 10px; color: #64748b;
    font-family: 'Courier New', monospace;
}
.kb-url-link { color: #38bdf8; font-weight: 600; }
.kb-warn {
    margin-top: 10px;
    display: flex; align-items: center; gap: 6px;
    font-size: 9.5px; color: #f59e0b;
    font-family: 'Courier New', monospace;
}

/* ── Info cetak ── */
.kartu-meta {
    margin-top: 12px;
    font-size: 11px;
    color: #9ca3af;
    text-align: center;
}

/* ══════════════════════════════
   PRINT — cetak kartu saja
══════════════════════════════ */
@media print {
    /* Paksa browser cetak background color & gradient */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    /* Sembunyikan semua elemen AdminLTE */
    .main-header,
    .main-sidebar,
    .content-header,
    .breadcrumb,
    .kartu-actions,
    .alert-member,
    .kartu-meta,
    footer,
    .navbar,
    [class*="sidebar"],
    .control-sidebar {
        display: none !important;
    }

    /* Reset layout wrapper AdminLTE */
    body, .wrapper, .content-wrapper, .content {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        width: 100% !important;
    }

    /* Tampilkan hanya kartu */
    .kartu-page {
        padding: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: flex-start !important;
        min-height: unset !important;
    }

    .kartu-preview-wrap {
        max-width: 100% !important;
        width: auto !important;
    }

    /* Kartu depan cetak */
    .kartu-depan {
        width: 85.6mm !important;
        height: 53.98mm !important;
        border-radius: 4mm !important;
        box-shadow: none !important;
        padding: 5mm 6mm !important;
        margin-bottom: 6mm !important;
        page-break-inside: avoid;
        /* Paksa cetak gradient */
        background: linear-gradient(135deg, #005F73 0%, #0a9396 55%, #2BB1B1 100%) !important;
    }
    .kartu-depan::before,
    .kartu-depan::after {
        background: rgba(255,255,255,.07) !important;
    }
    .kd-nama  { font-size: 14px !important; margin-top: 10px !important; }
    .kd-hp    { font-size: 9px !important; }
    .kd-brand { font-size: 11px !important; }
    .kd-badge {
        font-size: 8px !important;
        padding: 2px 8px !important;
        background: rgba(255,255,255,.18) !important;
        border: 1.5px solid rgba(255,255,255,.4) !important;
    }
    .kd-info-value { font-size: 9px !important; }

    /* Kartu belakang cetak */
    .kartu-belakang {
        width: 85.6mm !important;
        border-radius: 4mm !important;
        box-shadow: none !important;
        page-break-inside: avoid;
        background: linear-gradient(160deg, #0f172a 0%, #1e293b 100%) !important;
    }
    .kb-field-box {
        background: #0f172a !important;
        border-color: #334155 !important;
    }
    .kb-field-val { font-size: 12px !important; color: #f8fafc !important; }
    .kb-url {
        background: #0f172a !important;
        font-size: 8px !important;
        color: #64748b !important;
    }
    .kb-url-link { color: #38bdf8 !important; }
    .kb-warn { font-size: 8px !important; color: #f59e0b !important; }
    .kb-head-title { font-size: 9px !important; color: #94a3b8 !important; }
    .kb-field-tag {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #475569 !important;
    }

    @page {
        size: A4 portrait;
        margin: 15mm;
    }
}
</style>
@endpush

@section('content')
<div class="kartu-page">

    {{-- Alert sukses --}}
    <div class="alert-member">
        <i class="fas fa-check-circle fa-lg text-success"></i>
        <div>
            <strong>Akun berhasil dibuat!</strong>
            Kartu member untuk <strong>{{ $pelanggan->nama_pelanggan ?? $user->nama_user }}</strong>
            sudah siap. Cetak dan berikan kepada pelanggan.
        </div>
    </div>

    {{-- Tombol aksi --}}
    <div class="kartu-actions">
        <a href="{{ route('admin.register.customer.kartu.cetak', ['id' => $user->id_user, 'tk' => request('tk')]) }}"
           target="_blank" class="btn btn-dark px-4">
            <i class="fas fa-print mr-2"></i>Cetak Kartu
        </a>
        <a href="{{ route('admin.pelanggans.index') }}" class="btn btn-primary px-4">
            <i class="fas fa-users mr-2"></i>Ke Daftar Pelanggan
        </a>
    </div>

    {{-- Preview Kartu --}}
    <div class="kartu-preview-wrap">

        {{-- ── Sisi Depan ── --}}
        <div class="kartu-depan">
            <div class="kd-logo">
                <div class="kd-logo-icon">👕</div>
                <div>
                    <div class="kd-brand">Sofia Laundry</div>
                    <div class="kd-sub">Kartu Member Pelanggan</div>
                </div>
            </div>

            <div class="kd-nama">{{ $pelanggan->nama_pelanggan ?? $user->nama_user }}</div>
            <div class="kd-hp">{{ $pelanggan->no_hp ?? '-' }}</div>

            <div class="kd-bottom">
                <div>
                    <div class="kd-info-label">Member Sejak</div>
                    <div class="kd-info-value">{{ now()->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="kd-info-label">ID Pelanggan</div>
                    <div class="kd-info-value">#{{ str_pad($pelanggan->id_pelanggan ?? 0, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <span class="kd-badge">MEMBER</span>
                </div>
            </div>
        </div>

        {{-- ── Sisi Belakang / Info Login ── --}}
        <div class="kartu-belakang">
            <div class="kb-head">
                <div class="kb-head-icon">🔐</div>
                <div class="kb-head-title">Informasi Login Aplikasi</div>
            </div>
            <div class="kb-body">

                <div class="kb-field">
                    <div class="kb-field-label">Username</div>
                    <div class="kb-field-box">
                        <span class="kb-field-val">{{ $user->username }}</span>
                        <span class="kb-field-tag">USERNAME</span>
                    </div>
                </div>

                <div class="kb-field">
                    <div class="kb-field-label">Password</div>
                    <div class="kb-field-box">
                        <span class="kb-field-val">{{ $password }}</span>
                        <span class="kb-field-tag">PASSWORD</span>
                    </div>
                </div>

                <div class="kb-url">
                    <i class="fas fa-globe" style="color:#38bdf8;"></i>
                    <span>Login: <span class="kb-url-link">{{ url('/pelanggan/masuk') }}</span></span>
                </div>

                <div class="kb-warn">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Simpan kartu ini. Jangan berikan kepada orang lain.</span>
                </div>

            </div>
        </div>

        <div class="kartu-meta">
            <i class="fas fa-clock mr-1"></i>Dicetak {{ now()->format('d F Y, H:i') }}
            &nbsp;&middot;&nbsp;
            <i class="fas fa-user mr-1"></i>Kasir: {{ auth()->user()->nama_user }}
        </div>

    </div>
</div>
@endsection
