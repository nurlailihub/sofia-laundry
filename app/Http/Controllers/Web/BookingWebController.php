<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingWebController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['pelanggan', 'layanan', 'layanans.layanan', 'transaksi.pembayaran'])
            ->orderBy('tanggal_booking', 'desc')
            ->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function showConfirmForm($id)
    {
        $booking = Booking::with(['pelanggan', 'layanan', 'layanans.layanan'])->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Booking ini sudah ' . $booking->status . ', tidak bisa dikonfirmasi ulang.');
        }

        $layanans    = Layanan::all();
        $stokBarangs = \App\Models\StokBarang::where('stok', '>', 0)->get();
        $tarifDefault = \App\Models\TarifAntarJemput::all()->keyBy('tipe');

        return view('admin.bookings.confirm', compact('booking', 'layanans', 'stokBarangs', 'tarifDefault'));
    }

    public function confirm(Request $request, $id)
    {
        $booking = Booking::with(['pelanggan', 'layanan'])->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')
                ->with('error', 'Booking ini sudah ' . $booking->status . ', tidak bisa dikonfirmasi ulang.');
        }

        $request->validate([
            'details'              => 'required|array|min:1',
            'details.*.id_layanan' => 'required|exists:layanans,id_layanan',
            'details.*.berat'      => 'required|numeric|min:0.1',
            'id_pewangi'           => 'nullable|exists:stok_barangs,id_barang',
            'biaya_antar_jemput'   => 'nullable|numeric|min:0',
            'catatan_admin'        => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $totalBerat = 0;
            $totalHarga = 0;
            $detailData = [];

            foreach ($request->details as $d) {
                $layanan      = Layanan::findOrFail($d['id_layanan']);
                $berat        = (float) $d['berat'];
                $subtotal     = $berat * $layanan->harga_per_kg;
                $totalBerat  += $berat;
                $totalHarga  += $subtotal;
                $detailData[] = [
                    'id_layanan' => $d['id_layanan'],
                    'berat'      => $berat,
                    'subtotal'   => $subtotal,
                ];
            }

            if ($request->id_pewangi) {
                $pewangi = \App\Models\StokBarang::findOrFail($request->id_pewangi);
                if ($pewangi->stok < 1) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Stok pewangi tidak mencukupi.')
                        ->withInput();
                }
                $pewangi->decrement('stok', 1);
            }

            $biayaAntar = (float)($request->biaya_antar_jemput ?? 0);

            $transaksi = Transaksi::create([
                'id_pelanggan'       => $booking->id_pelanggan,
                'id_user'            => auth()->id(),
                'id_booking'         => $booking->id_booking,
                'id_pewangi'         => $request->id_pewangi ?? null,
                'tanggal_masuk'      => now(),
                'total_berat'        => $totalBerat,
                'total_harga'        => $totalHarga,
                'tipe_antar_jemput'  => $booking->tipe_antar_jemput,
                'biaya_antar_jemput' => $biayaAntar,
                'status'             => 'proses',
                'status_detail'      => 'diterima',
                'catatan_status'     => $request->catatan_admin,
            ]);

            foreach ($detailData as $d) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_layanan'   => $d['id_layanan'],
                    'berat'        => $d['berat'],
                    'subtotal'     => $d['subtotal'],
                ]);
            }

            $totalTagihan = $totalHarga + $biayaAntar;
            $dpBayar      = $booking->dp_bayar ?? 0;
            $statusBayar  = ($dpBayar >= $totalTagihan && $totalTagihan > 0) ? 'lunas' : 'belum';

            Pembayaran::create([
                'id_transaksi'  => $transaksi->id_transaksi,
                'tanggal_bayar' => now(),
                'metode_bayar'  => $booking->metode_bayar ?? 'cash',
                'jumlah_bayar'  => $dpBayar,
                'status_bayar'  => $statusBayar,
                'catatan'       => $dpBayar > 0 ? 'DP dari booking ' . ($booking->kode_reservasi ?? '') : null,
            ]);

            $sisaBayar = max(0, $totalTagihan - $dpBayar);
            $booking->update([
                'status'             => 'confirmed',
                'biaya_antar_jemput' => $biayaAntar,
                'sisa_bayar'         => $sisaBayar,
            ]);

            DB::commit();

            return redirect()->route('admin.bookings.faktur', $booking->id_booking)
                ->with('success', 'Booking ' . ($booking->kode_reservasi ?? '') . ' dikonfirmasi. Transaksi dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengkonfirmasi booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Booking ini sudah tidak bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus.');
    }

    public function faktur($id)
    {
        $booking = Booking::with(['pelanggan', 'layanan', 'transaksi.pembayaran', 'transaksi.detailTransaksi.layanan'])
            ->findOrFail($id);

        return view('admin.bookings.faktur', compact('booking'));
    }

    public function cetakFaktur($id)
    {
        $booking = Booking::with(['pelanggan', 'layanan', 'transaksi.pembayaran', 'transaksi.detailTransaksi.layanan'])
            ->findOrFail($id);

        $view = view('admin.bookings.faktur-print', compact('booking'))->render();

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view)->setPaper('a5', 'portrait');
            return $pdf->download('faktur-booking-' . str_pad($booking->id_booking, 6, '0', STR_PAD_LEFT) . '.pdf');
        }

        return view('admin.bookings.faktur-print', compact('booking'));
    }

    public function bayarDp(Request $request, $id)
    {
        $booking = Booking::with(['transaksi.pembayaran'])->findOrFail($id);

        $request->validate([
            'jumlah_dp'    => 'required|numeric|min:0',
            'metode_bayar' => 'required|in:cash,transfer,qris',
        ]);

        $transaksi    = $booking->transaksi;
        $totalTagihan = $transaksi ? $transaksi->total_tagihan : 0;
        $dpBaru       = ($booking->dp_bayar ?? 0) + $request->jumlah_dp;
        $sisaBayar    = max(0, $totalTagihan - $dpBaru);

        $booking->update([
            'dp_bayar'     => $dpBaru,
            'sisa_bayar'   => $sisaBayar,
            'metode_bayar' => $request->metode_bayar,
        ]);

        if ($transaksi && $transaksi->pembayaran) {
            $statusBayar = ($totalTagihan > 0 && $dpBaru >= $totalTagihan) ? 'lunas' : 'belum';
            $transaksi->pembayaran->update([
                'metode_bayar' => $request->metode_bayar,
                'jumlah_bayar' => $dpBaru,
                'status_bayar' => $statusBayar,
            ]);
        }

        $pesan = 'DP berhasil dicatat. Sisa bayar: Rp ' . number_format($sisaBayar, 0, ',', '.');
        if ($totalTagihan > 0 && $dpBaru >= $totalTagihan) {
            $pesan = 'Pembayaran lunas! Total: Rp ' . number_format($totalTagihan, 0, ',', '.');
        }

        return redirect()->back()->with('success', $pesan);
    }
}
