<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Member – {{ $pelanggan->nama_pelanggan ?? $user->nama_user }}</title>
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 30px 20px;
            min-height: 100vh;
        }

        /* ── Kartu Depan ── */
        .kartu-depan {
            width: 85.6mm;
            height: 53.98mm;
            border-radius: 4mm;
            background: linear-gradient(135deg, #005F73 0%, #0a9396 55%, #2BB1B1 100%);
            color: white;
            padding: 5mm 6mm;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,95,115,.4);
            margin-bottom: 6mm;
        }
        .kartu-depan::before {
            content: '';
            position: absolute;
            width: 55mm; height: 55mm;
            border-radius: 50%;
            background: rgba(255,255,255,.07);
            top: -20mm; right: -12mm;
        }
        .kartu-depan::after {
            content: '';
            position: absolute;
            width: 35mm; height: 35mm;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            bottom: -10mm; left: -6mm;
        }
        .kd-logo { display: flex; align-items: center; gap: 6px; margin-bottom: 2px; }
        .kd-logo-icon {
            width: 8mm; height: 8mm;
            background: rgba(255,255,255,.2);
            border-radius: 2mm;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
        }
        .kd-brand { font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
        .kd-sub   { font-size: 7px; opacity: .6; letter-spacing: .8px; text-transform: uppercase; }
        .kd-nama  { font-size: 14px; font-weight: 800; letter-spacing: .3px; margin-top: 6mm; margin-bottom: 1mm; position: relative; z-index: 1; }
        .kd-hp    { font-size: 9px; opacity: .8; position: relative; z-index: 1; margin-bottom: 5mm; }
        .kd-bottom { display: flex; justify-content: space-between; align-items: flex-end; position: relative; z-index: 1; }
        .kd-info-label { font-size: 7px; opacity: .6; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 1px; }
        .kd-info-value { font-size: 9px; font-weight: 700; }
        .kd-badge {
            background: rgba(255,255,255,.18);
            border: 1.5px solid rgba(255,255,255,.4);
            border-radius: 5mm;
            padding: 1.5px 4mm;
            font-size: 8px; font-weight: 800;
            letter-spacing: 2px;
        }

        /* ── Kartu Belakang ── */
        .kartu-belakang {
            width: 85.6mm;
            border-radius: 4mm;
            background: linear-gradient(160deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,.3);
        }
        .kb-head {
            padding: 3mm 5mm 3mm;
            border-bottom: 1px solid #334155;
            display: flex; align-items: center; gap: 6px;
        }
        .kb-head-icon {
            width: 7mm; height: 7mm;
            background: #1e3a5f;
            border-radius: 2mm;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
        }
        .kb-head-title { font-size: 9px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: #94a3b8; }

        .kb-body { padding: 4mm 5mm; }
        .kb-field { margin-bottom: 3.5mm; }
        .kb-field:last-child { margin-bottom: 0; }
        .kb-field-label {
            font-size: 7px; color: #64748b;
            text-transform: uppercase; letter-spacing: .8px;
            margin-bottom: 1.5mm;
            font-family: 'Courier New', monospace;
        }
        .kb-field-box {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 2.5mm;
            padding: 2.5mm 3.5mm;
            display: flex; justify-content: space-between; align-items: center;
        }
        .kb-field-val {
            font-family: 'Courier New', monospace;
            font-size: 12px; font-weight: 700;
            color: #f8fafc;
            letter-spacing: 1px;
        }
        .kb-field-tag {
            font-size: 7px; color: #475569;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 1mm;
            padding: 1px 2mm;
            font-family: 'Courier New', monospace;
        }
        .kb-url {
            margin-top: 3mm;
            padding: 2.5mm 3.5mm;
            background: #0f172a;
            border-radius: 2mm;
            display: flex; align-items: center; gap: 6px;
            font-size: 8px; color: #64748b;
            font-family: 'Courier New', monospace;
        }
        .kb-url-link { color: #38bdf8; font-weight: 600; }
        .kb-warn {
            margin-top: 2.5mm;
            display: flex; align-items: center; gap: 5px;
            font-size: 8px; color: #f59e0b;
            font-family: 'Courier New', monospace;
        }

        /* ── Tombol (hanya tampil di layar, tidak dicetak) ── */
        .print-actions {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .btn-print {
            background: #1e293b; color: white;
            border: none; border-radius: 8px;
            padding: 10px 24px; font-size: 14px;
            cursor: pointer; display: flex; align-items: center; gap: 8px;
        }
        .btn-print:hover { background: #334155; }
        .btn-back {
            background: #0ea5e9; color: white;
            border: none; border-radius: 8px;
            padding: 10px 24px; font-size: 14px;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-back:hover { background: #0284c7; }

        .kartu-meta {
            margin-top: 10px;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }

        @media print {
            body {
                background: white !important;
                padding: 10mm !important;
            }
            .print-actions,
            .kartu-meta {
                display: none !important;
            }
            .kartu-depan {
                box-shadow: none !important;
            }
            .kartu-belakang {
                box-shadow: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    {{-- Tombol (hanya di layar) --}}
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak Kartu
        </button>
        <a class="btn-back" href="{{ route('admin.register.customer.kartu', ['id' => $user->id_user, 'tk' => request('tk')]) }}">
            ← Kembali
        </a>
    </div>

    {{-- Kartu Depan --}}
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

    {{-- Kartu Belakang --}}
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
                    <span class="kb-field-val">{{ $password ?: '(tidak tersedia)' }}</span>
                    <span class="kb-field-tag">PASSWORD</span>
                </div>
            </div>

            <div class="kb-url">
                🌐 Login: <span class="kb-url-link">{{ url('/pelanggan/masuk') }}</span>
            </div>

            <div class="kb-warn">
                ⚠️ Simpan kartu ini. Jangan berikan kepada orang lain.
            </div>

        </div>
    </div>

    <div class="kartu-meta">
        🕐 Dicetak {{ now()->format('d F Y, H:i') }} &middot; Kasir: {{ auth()->user()->nama_user }}
    </div>

    {{-- Auto print saat halaman dibuka --}}
    <script>
        window.onload = function () {
            window.print();
        };
    </script>

</body>
</html>
