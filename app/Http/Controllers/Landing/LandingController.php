<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $layanans = Layanan::all();
        return view('landing.index', compact('layanans'));
    }

    public function cekStatusForm()
    {
        $layanans = Layanan::all();
        return view('landing.index', compact('layanans'));
    }

    public function bookingSukses(Request $request)
    {
        $booking = Booking::with(['pelanggan', 'layanan'])->findOrFail($request->kode);
        return view('landing.booking-success', compact('booking'));
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ], [
            'keyword.required' => 'Masukkan nomor HP atau Kode Booking / Transaksi.',
        ]);

        $keyword  = trim($request->keyword);
        $layanans = Layanan::all();

        $cleanPhone = preg_replace('/[^0-9]/', '', $keyword);
        $pelanggan  = null;

        if (!empty($cleanPhone) && strlen($cleanPhone) >= 4) {
            $lastDigits = strlen($cleanPhone) >= 8 ? substr($cleanPhone, -8) : $cleanPhone;
            $pelanggan  = Pelanggan::where('no_hp', 'like', '%' . $lastDigits . '%')->first();
        }

        if (!$pelanggan) {
            $cleanCode    = preg_replace('/[^0-9]/', '', $keyword);
            $bookingMatch = Booking::with('pelanggan')
                ->where('kode_reservasi', 'like', '%' . $keyword . '%')
                ->orWhere('id_booking', $cleanCode)
                ->first();

            if ($bookingMatch && $bookingMatch->pelanggan) {
                $pelanggan = $bookingMatch->pelanggan;
            } else {
                $transaksiMatch = Transaksi::with('pelanggan')
                    ->where('id_transaksi', $cleanCode)
                    ->first();
                if ($transaksiMatch && $transaksiMatch->pelanggan) {
                    $pelanggan = $transaksiMatch->pelanggan;
                }
            }
        }

        if (!$pelanggan) {
            return redirect()->route('landing.index')
                ->with('error', 'Data pesanan atau nomor HP tidak ditemukan dalam sistem kami.')
                ->withInput()
                ->withFragment('cek-status');
        }

        $transaksis = Transaksi::with(['detailTransaksi.layanan', 'pembayaran'])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderBy('created_at', 'desc')
            ->get();

        $bookings = Booking::with(['layanan'])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderBy('tanggal_booking', 'desc')
            ->get();

        return view('landing.index', compact('layanans', 'pelanggan', 'transaksis', 'bookings'));
    }
}
