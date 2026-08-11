<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembayaranWebController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['transaksi.pelanggan'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.pembayarans.index', compact('pembayarans'));
    }

    public function create($id_transaksi)
    {
        $transaksi = Transaksi::with([
            'pelanggan',
            'detailTransaksi.layanan',
            'pewangi',
            'booking',
            'pembayaran',
        ])->findOrFail($id_transaksi);

        if ($transaksi->pembayaran && $transaksi->pembayaran->status_bayar === 'lunas') {
            return redirect()->route('admin.pembayarans.faktur', $transaksi->pembayaran->id_pembayaran)
                ->with('info', 'Transaksi ini sudah lunas.');
        }

        $metodeOptions = Pembayaran::$metodeLabels;
        $totalTagihan  = $transaksi->total_tagihan;

        return view('admin.pembayarans.create', compact('transaksi', 'metodeOptions', 'totalTagihan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_transaksi'    => 'required|exists:transaksis,id_transaksi',
            'metode_bayar'    => 'required|in:cash,transfer,qris',
            'jumlah_bayar'    => 'required|numeric|min:0',
            'nomor_referensi' => 'nullable|string|max:100',
            'catatan'         => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $transaksi = Transaksi::findOrFail($request->id_transaksi);

        $totalTagihan     = $transaksi->total_tagihan;

        $sudahBayar      = $transaksi->pembayaran?->jumlah_bayar ?? 0;
        $jumlahTotalBayar = $sudahBayar + $request->jumlah_bayar;
        $status           = ($totalTagihan > 0 && $jumlahTotalBayar >= $totalTagihan) ? 'lunas' : 'belum';

        $pembayaran = Pembayaran::updateOrCreate(
            ['id_transaksi' => $request->id_transaksi],
            [
                'tanggal_bayar'    => now(),
                'metode_bayar'     => $request->metode_bayar,
                'jumlah_bayar'     => $jumlahTotalBayar,
                'nomor_referensi'  => $request->nomor_referensi,
                'catatan'          => $request->catatan,
                'status_bayar'     => $status,
            ]
        );

        if ($status === 'lunas' && $transaksi->status === 'selesai') {
            $transaksi->update(['status' => 'diambil']);
        }

        return redirect()->route('admin.pembayarans.faktur', $pembayaran->id_pembayaran)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function faktur($id)
    {
        $pembayaran = Pembayaran::with([
            'transaksi.pelanggan',
            'transaksi.detailTransaksi.layanan',
            'transaksi.pewangi',
            'transaksi.user',
            'transaksi.booking',
        ])->findOrFail($id);

        $tipeFaktur = 'selesai';
        if ($pembayaran->transaksi->booking && in_array($pembayaran->transaksi->booking->tipe_antar_jemput, ['pickup', 'both'])) {
            $tipeFaktur = 'jemput';
        }

        return view('admin.pembayarans.faktur', compact('pembayaran', 'tipeFaktur'));
    }

    public function cetakFaktur($id)
    {
        $pembayaran = Pembayaran::with([
            'transaksi.pelanggan',
            'transaksi.detailTransaksi.layanan',
            'transaksi.pewangi',
            'transaksi.user',
            'transaksi.booking',
        ])->findOrFail($id);

        $tipeFaktur = 'selesai';
        if ($pembayaran->transaksi->booking && in_array($pembayaran->transaksi->booking->tipe_antar_jemput, ['pickup', 'both'])) {
            $tipeFaktur = 'jemput';
        }

        $view = view('admin.pembayarans.faktur-print', compact('pembayaran', 'tipeFaktur'))->render();

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view)->setPaper('a5', 'portrait');
            return $pdf->download('faktur-' . $pembayaran->nomor_faktur . '.pdf');
        }

        return view('admin.pembayarans.faktur-print', compact('pembayaran', 'tipeFaktur'));
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('admin.pembayarans.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
