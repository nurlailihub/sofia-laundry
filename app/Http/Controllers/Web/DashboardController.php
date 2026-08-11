<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now();

        $totalPelanggan     = Pelanggan::count();
        $totalUser          = User::whereIn('role', ['admin', 'pimpinan'])->count();
        $pendapatanHariIni  = Transaksi::whereDate('tanggal_masuk', $today)->sum('total_harga');
        $pendapatanBulanIni = Transaksi::whereMonth('tanggal_masuk', $today->month)
            ->whereYear('tanggal_masuk', $today->year)
            ->sum('total_harga');
        $cucianMasukHariIni = Transaksi::whereDate('tanggal_masuk', $today)->count();
        $cucianProses       = Transaksi::where('status', 'proses')->count();
        $cucianSelesai      = Transaksi::where('status', 'selesai')->count();
        $cucianDiambil      = Transaksi::where('status', 'diambil')->count();
        $pelangganBaru      = Pelanggan::where('created_at', '>=', now()->subDays(7))->count();
        $pelangganTerbaru   = Pelanggan::orderBy('created_at', 'desc')->limit(5)->get();

        $statusDetailLabels = Transaksi::$statusDetailLabels;
        $statusDetailIcons  = Transaksi::$statusDetailIcons;
        $statusDetailColors = Transaksi::$statusDetailColors;

        $monitoringRingkas = Transaksi::with('pelanggan')
            ->whereNotIn('status', ['diambil'])
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPelanggan',
            'totalUser',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'cucianMasukHariIni',
            'cucianProses',
            'cucianSelesai',
            'cucianDiambil',
            'pelangganBaru',
            'pelangganTerbaru',
            'statusDetailLabels',
            'statusDetailIcons',
            'statusDetailColors',
            'monitoringRingkas'
        ));
    }
}
