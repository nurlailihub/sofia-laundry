<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Booking;
use Carbon\Carbon;

class PimpinanDashboardController extends Controller
{
    public function index()
    {
        $today      = now();
        $bulanIni   = $today->month;
        $tahunIni   = $today->year;

        $pendapatanHariIni = Transaksi::with('pembayaran')
            ->whereDate('tanggal_masuk', $today)
            ->get()
            ->sum(fn($t) => (float) $t->total_tagihan);

        $pendapatanBulanIni = Transaksi::with('pembayaran')
            ->whereMonth('tanggal_masuk', $bulanIni)
            ->whereYear('tanggal_masuk', $tahunIni)
            ->get()
            ->sum(fn($t) => (float) $t->total_tagihan);

        $pendapatanTerbayarBulanIni = Pembayaran::whereHas('transaksi', function ($q) use ($bulanIni, $tahunIni) {
            $q->whereMonth('tanggal_masuk', $bulanIni)->whereYear('tanggal_masuk', $tahunIni);
        })->where('status_bayar', 'lunas')->sum('jumlah_bayar');

        $totalTransaksiBulanIni = Transaksi::whereMonth('tanggal_masuk', $bulanIni)
            ->whereYear('tanggal_masuk', $tahunIni)
            ->count();

        $totalPelanggan     = Pelanggan::count();
        $pelangganBaru      = Pelanggan::where('created_at', '>=', now()->subDays(30))->count();

        $bookingPending = Booking::where('status', 'pending')->count();

        $transaksiPiutang = Transaksi::with('pembayaran')
            ->whereHas('pembayaran', fn($q) => $q->where('status_bayar', 'belum'))
            ->count();

        $perBulan = Transaksi::selectRaw('MONTH(tanggal_masuk) as bulan, YEAR(tanggal_masuk) as tahun, COUNT(*) as total_transaksi, SUM(total_harga) as total_harga')
            ->whereYear('tanggal_masuk', $tahunIni)
            ->groupByRaw('YEAR(tanggal_masuk), MONTH(tanggal_masuk)')
            ->orderByRaw('MONTH(tanggal_masuk)')
            ->get()
            ->map(function ($row) {
                return [
                    'bulan'            => Carbon::create(null, $row->bulan)->translatedFormat('F'),
                    'total_transaksi'  => $row->total_transaksi,
                    'total_pendapatan' => (float) $row->total_harga,
                ];
            });

        $labelsChart      = $perBulan->pluck('bulan')->toJson();
        $dataTransaksi    = $perBulan->pluck('total_transaksi')->toJson();
        $dataPendapatan   = $perBulan->pluck('total_pendapatan')->toJson();

        $transaksiTerbaru = Transaksi::with(['pelanggan', 'pembayaran'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('pimpinan.dashboard', compact(
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'pendapatanTerbayarBulanIni',
            'totalTransaksiBulanIni',
            'totalPelanggan',
            'pelangganBaru',
            'bookingPending',
            'transaksiPiutang',
            'labelsChart',
            'dataTransaksi',
            'dataPendapatan',
            'transaksiTerbaru',
            'tahunIni'
        ));
    }
}
