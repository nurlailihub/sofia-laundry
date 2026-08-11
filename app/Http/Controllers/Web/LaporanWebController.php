<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
class LaporanWebController extends Controller
{
    public function transaksiIndex(Request $request)
    {
        $pelanggans = Pelanggan::all();

        $query = Transaksi::with(['pelanggan', 'user', 'pewangi', 'detailTransaksi.layanan', 'pembayaran']);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('id_pelanggan')) {
            $query->where('id_pelanggan', $request->id_pelanggan);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();

        $statistik = [
            'total_transaksi'  => $transaksis->count(),
            'total_pendapatan' => (float) $transaksis->sum('total_harga'),
            'total_berat'      => (float) $transaksis->sum('total_berat'),
            'status_proses'    => $transaksis->where('status', 'proses')->count(),
            'status_selesai'   => $transaksis->where('status', 'selesai')->count(),
            'status_diambil'   => $transaksis->where('status', 'diambil')->count(),
        ];

        return view('admin.laporan.transaksi', compact('pelanggans', 'transaksis', 'statistik'));
    }

    public function transaksiData(Request $request)
    {
        try {
            $query = Transaksi::with(['pelanggan', 'user', 'pewangi', 'detailTransaksi.layanan', 'pembayaran']);

            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
            }

            if ($request->filled('tanggal_akhir')) {
                $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('id_pelanggan')) {
                $query->where('id_pelanggan', $request->id_pelanggan);
            }

            $transaksis = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'transaksis' => $transaksis,
                    'statistik'  => [
                        'total_transaksi'  => $transaksis->count(),
                        'total_pendapatan' => (float) $transaksis->sum('total_harga'),
                        'total_berat'      => (float) $transaksis->sum('total_berat'),
                        'status_proses'    => $transaksis->where('status', 'proses')->count(),
                        'status_selesai'   => $transaksis->where('status', 'selesai')->count(),
                        'status_diambil'   => $transaksis->where('status', 'diambil')->count(),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function transaksiCetak(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'user', 'pewangi', 'detailTransaksi.layanan', 'pembayaran']);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_pelanggan')) {
            $query->where('id_pelanggan', $request->id_pelanggan);
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'transaksis'     => $transaksis,
            'statistik'      => [
                'total_transaksi'  => $transaksis->count(),
                'total_pendapatan' => (float) $transaksis->sum('total_harga'),
                'total_berat'      => (float) $transaksis->sum('total_berat'),
                'status_proses'    => $transaksis->where('status', 'proses')->count(),
                'status_selesai'   => $transaksis->where('status', 'selesai')->count(),
                'status_diambil'   => $transaksis->where('status', 'diambil')->count(),
            ],
            'tanggal_mulai'  => $request->filled('tanggal_mulai')
                ? \Carbon\Carbon::parse($request->tanggal_mulai)->format('d/m/Y')
                : null,
            'tanggal_akhir'  => $request->filled('tanggal_akhir')
                ? \Carbon\Carbon::parse($request->tanggal_akhir)->format('d/m/Y')
                : null,
            'status_filter'  => $request->status
                ? ucfirst($request->status)
                : 'Semua',
            'nama_filter'    => $request->id_pelanggan
                ? (Pelanggan::find($request->id_pelanggan)->nama_pelanggan ?? 'Semua')
                : 'Semua',
            'tanggal_cetak'  => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf.transaksi', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('laporan-transaksi-' . date('Y-m-d') . '.pdf');
    }

    public function pelangganIndex(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?: null;
        $tanggalAkhir = $request->tanggal_akhir ?: null;

        $query = Pelanggan::query();

        if ($request->filled('nama_pelanggan')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }
        if ($tanggalMulai || $tanggalAkhir) {
            $query->whereHas('transaksis', function ($q) use ($tanggalMulai, $tanggalAkhir) {
                if ($tanggalMulai) $q->whereDate('tanggal_masuk', '>=', $tanggalMulai);
                if ($tanggalAkhir) $q->whereDate('tanggal_masuk', '<=', $tanggalAkhir);
            });
        }

        $pelanggans = $query->orderBy('created_at', 'desc')->get()->map(function ($p) use ($tanggalMulai, $tanggalAkhir) {
            $q = Transaksi::where('id_pelanggan', $p->id_pelanggan);
            if ($tanggalMulai) $q->whereDate('tanggal_masuk', '>=', $tanggalMulai);
            if ($tanggalAkhir) $q->whereDate('tanggal_masuk', '<=', $tanggalAkhir);
            $trx = $q->get();
            $p->total_transaksi    = $trx->count();
            $p->total_pendapatan   = (float) $trx->sum('total_harga');
            $p->transaksi_terakhir = $trx->max('tanggal_masuk');
            return $p;
        });

        $statistik = [
            'total_pelanggan'              => $pelanggans->count(),
            'pelanggan_aktif'              => $pelanggans->where('total_transaksi', '>', 0)->count(),
            'total_transaksi_keseluruhan'  => $pelanggans->sum('total_transaksi'),
            'total_pendapatan_keseluruhan' => (float) $pelanggans->sum('total_pendapatan'),
        ];

        return view('admin.laporan.pelanggan', compact('pelanggans', 'statistik'));
    }

    public function pelangganData(Request $request)
    {
        try {
            $query        = Pelanggan::query();
            $tanggalMulai = $request->tanggal_mulai ?: null;
            $tanggalAkhir = $request->tanggal_akhir ?: null;

            if ($request->filled('nama_pelanggan')) {
                $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
            }

            if ($tanggalMulai || $tanggalAkhir) {
                $query->whereHas('transaksis', function ($q) use ($tanggalMulai, $tanggalAkhir) {
                    if ($tanggalMulai) {
                        $q->whereDate('tanggal_masuk', '>=', $tanggalMulai);
                    }
                    if ($tanggalAkhir) {
                        $q->whereDate('tanggal_masuk', '<=', $tanggalAkhir);
                    }
                });
            }

            $pelanggans = $query->orderBy('created_at', 'desc')->get()->map(function ($pelanggan) use ($tanggalMulai, $tanggalAkhir) {
                $q = \App\Models\Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan);
                if ($tanggalMulai) {
                    $q->whereDate('tanggal_masuk', '>=', $tanggalMulai);
                }
                if ($tanggalAkhir) {
                    $q->whereDate('tanggal_masuk', '<=', $tanggalAkhir);
                }
                $trx                           = $q->get();
                $pelanggan->total_transaksi    = $trx->count();
                $pelanggan->total_pendapatan   = (float) $trx->sum('total_harga');
                $pelanggan->transaksi_terakhir = $trx->max('tanggal_masuk');
                return $pelanggan;
            });

            return response()->json([
                'success' => true,
                'data'    => [
                    'pelanggans' => $pelanggans,
                    'statistik'  => [
                        'total_pelanggan'              => $pelanggans->count(),
                        'pelanggan_aktif'              => $pelanggans->where('total_transaksi', '>', 0)->count(),
                        'total_transaksi_keseluruhan'  => $pelanggans->sum('total_transaksi'),
                        'total_pendapatan_keseluruhan' => (float) $pelanggans->sum('total_pendapatan'),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pelangganCetak(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->filled('nama_pelanggan')) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }

        $pelanggans = $query->orderBy('created_at', 'desc')->get()->map(function ($pelanggan) use ($request) {
            $q = \App\Models\Transaksi::where('id_pelanggan', $pelanggan->id_pelanggan);
            if ($request->filled('tanggal_mulai')) {
                $q->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
            }
            if ($request->filled('tanggal_akhir')) {
                $q->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
            }
            $trx                           = $q->get();
            $pelanggan->total_transaksi    = $trx->count();
            $pelanggan->total_pendapatan   = $trx->sum('total_harga');
            $pelanggan->transaksi_terakhir = $trx->max('tanggal_masuk');
            return $pelanggan;
        });

        $totalPelanggan = $pelanggans->count();
        $pelangganAktif = $pelanggans->where('total_transaksi', '>', 0)->count();

        $data = [
            'pelanggans'    => $pelanggans,
            'statistik'     => [
                'total_pelanggan'              => $totalPelanggan,
                'pelanggan_aktif'              => $pelangganAktif,
                'pelanggan_nonaktif'           => $totalPelanggan - $pelangganAktif,
                'total_transaksi_keseluruhan'  => $pelanggans->sum('total_transaksi'),
                'total_pendapatan_keseluruhan' => (float) $pelanggans->sum('total_pendapatan'),
            ],
            'nama_filter'   => $request->nama_pelanggan ?: 'Semua',
            'tanggal_mulai' => $request->filled('tanggal_mulai')
                ? \Carbon\Carbon::parse($request->tanggal_mulai)->format('d/m/Y')
                : null,
            'tanggal_akhir' => $request->filled('tanggal_akhir')
                ? \Carbon\Carbon::parse($request->tanggal_akhir)->format('d/m/Y')
                : null,
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf.pelanggan', $data);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('laporan-pelanggan-' . date('Y-m-d') . '.pdf');
    }

    public function pertahunIndex()
    {
        return view('admin.laporan.pertahun');
    }

    public function pertahunData(Request $request)
    {
        try {
            $query = Transaksi::query();

            if ($request->filled('tahun')) {
                $query->whereYear('tanggal_masuk', $request->tahun);
            }

            $transaksis = $query->orderBy('tanggal_masuk', 'asc')->get();

            $perBulan = $transaksis->groupBy(function ($t) {
                return \Carbon\Carbon::parse($t->tanggal_masuk)->format('Y-m');
            })->map(function ($items, $key) {
                $bulan = \Carbon\Carbon::parse($key . '-01');
                return [
                    'tahun'            => $bulan->format('Y'),
                    'bulan'            => $bulan->translatedFormat('F'),
                    'total_transaksi'  => $items->count(),
                    'total_pendapatan' => (float) $items->sum('total_harga'),
                    'total_berat'      => (float) $items->sum('total_berat'),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data'    => [
                    'perBulan'   => $perBulan,
                    'statistik'  => [
                        'total_transaksi'  => $transaksis->count(),
                        'total_pendapatan' => (float) $transaksis->sum('total_harga'),
                        'total_berat'      => (float) $transaksis->sum('total_berat'),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pertahunCetak(Request $request)
    {
        $query = Transaksi::query();

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }

        $transaksis = $query->orderBy('tanggal_masuk', 'asc')->get();

        $perBulan = $transaksis->groupBy(function ($t) {
            return \Carbon\Carbon::parse($t->tanggal_masuk)->format('Y-m');
        })->map(function ($items, $key) {
            $bulan = \Carbon\Carbon::parse($key . '-01');
            return [
                'tahun'            => $bulan->format('Y'),
                'bulan'            => $bulan->translatedFormat('F'),
                'total_transaksi'  => $items->count(),
                'total_pendapatan' => (float) $items->sum('total_harga'),
                'total_berat'      => (float) $items->sum('total_berat'),
            ];
        })->values();

        $data = [
            'perBulan'       => $perBulan,
            'statistik'      => [
                'total_transaksi'  => $transaksis->count(),
                'total_pendapatan' => (float) $transaksis->sum('total_harga'),
                'total_berat'      => (float) $transaksis->sum('total_berat'),
            ],
            'tahun_filter'   => $request->tahun ?? 'Semua Tahun',
            'tanggal_cetak'  => now()->format('d/m/Y H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf.pertahun', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('laporan-pertahun-' . date('Y-m-d') . '.pdf');
    }
}
