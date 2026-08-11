<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $pelanggan = auth()->user()->pelanggan;

        if (!$pelanggan) {
            return view('customer.dashboard', [
                'transaksis'      => collect(),
                'bookings'        => collect(),
                'transaksiAktif'  => null,
                'transaksiAktifs' => collect(),
            ]);
        }

        $transaksis = Transaksi::with(['detailTransaksi.layanan', 'pengembalian', 'pembayaran'])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderBy('created_at', 'desc')
            ->get();

        $bookings = Booking::with('layanan')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderBy('tanggal_booking', 'desc')
            ->take(5)
            ->get();

        $transaksiAktifs = $transaksis->whereNotIn('status', ['diambil'])->values();
        $transaksiAktif  = $transaksiAktifs->first();

        return view('customer.dashboard', compact('transaksis', 'bookings', 'transaksiAktif', 'transaksiAktifs', 'pelanggan'));
    }

    public function riwayat()
    {
        $pelanggan = auth()->user()->pelanggan;

        if (!$pelanggan) {
            return view('customer.riwayat', [
                'transaksis' => Transaksi::whereNull('id_transaksi')->paginate(10),
                'pelanggan'  => null,
            ]);
        }

        $transaksis = Transaksi::with(['detailTransaksi.layanan', 'pengembalian', 'pembayaran'])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.riwayat', compact('transaksis', 'pelanggan'));
    }

    public function bookingForm()
    {
        $layanans     = Layanan::all();
        $pelanggan    = auth()->user()->pelanggan;
        $layanansJson = $layanans->map(fn($l) => [
            'id'          => $l->id_layanan,
            'nama'        => $l->nama_layanan,
            'harga_per_kg'=> (float) $l->harga_per_kg,
        ]);

        $tarifs = \App\Models\TarifAntarJemput::all()->keyBy('tipe');
        $tarifJson = [
            'pickup'   => (float) ($tarifs['pickup']->harga   ?? 0),
            'delivery' => (float) ($tarifs['delivery']->harga ?? 0),
            'both'     => (float) ($tarifs['both']->harga     ?? 0),
        ];

        return view('customer.booking', compact('layanans', 'pelanggan', 'layanansJson', 'tarifJson'));
    }

    public function bookingStore(Request $request)
    {
        $pelanggan = auth()->user()->pelanggan;

        if (!$pelanggan) {
            return redirect()->route('customer.dashboard')->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'layanans'                  => 'required|array|min:1',
            'layanans.*.id_layanan'     => 'required|exists:layanans,id_layanan',
            'layanans.*.estimasi_berat' => 'nullable|numeric|min:0.1',
            'tanggal_booking'           => 'required|date|after_or_equal:today',
            'waktu_booking'             => 'nullable|date_format:H:i',
            'catatan'                   => 'nullable|string|max:500',
            'tipe_antar_jemput'         => 'required|in:none,pickup,delivery,both',
            'alamat_jemput'             => 'required_if:tipe_antar_jemput,pickup,both|nullable|string',
            'alamat_antar'              => 'required_if:tipe_antar_jemput,delivery,both|nullable|string',
            'metode_bayar'              => 'required|in:cash,transfer,qris',
            'bukti_pembayaran'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'dp_bayar'                  => 'nullable|numeric|min:0',
        ], [
            'layanans.required'              => 'Pilih minimal satu layanan.',
            'layanans.*.id_layanan.required' => 'Layanan tidak boleh kosong.',
            'layanans.*.id_layanan.exists'   => 'Layanan tidak valid.',
            'tanggal_booking.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'alamat_jemput.required_if'      => 'Alamat penjemputan wajib diisi.',
            'alamat_antar.required_if'       => 'Alamat pengantaran wajib diisi.',
            'metode_bayar.required'          => 'Pilih metode pembayaran.',
            'bukti_pembayaran.image'         => 'File harus berupa gambar (jpg/png/webp).',
            'bukti_pembayaran.max'           => 'Ukuran file maksimal 2 MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');
        }

        $totalEstimasiBerat = 0;
        $totalEstimasiHarga = 0;
        $layananDetails     = [];

        foreach ($request->layanans as $item) {
            $layanan = \App\Models\Layanan::find($item['id_layanan']);
            if (!$layanan) continue;
            $berat    = floatval($item['estimasi_berat'] ?? 0);
            $subtotal = $berat * floatval($layanan->harga_per_kg);
            $totalEstimasiBerat += $berat;
            $totalEstimasiHarga += $subtotal;
            $layananDetails[] = [
                'id_layanan'        => $layanan->id_layanan,
                'estimasi_berat'    => $berat,
                'estimasi_subtotal' => $subtotal,
            ];
        }

        $dpBayar   = min($request->dp_bayar ?? 0, $totalEstimasiHarga);
        $sisaBayar = max(0, $totalEstimasiHarga - $dpBayar);

        $booking = \App\Models\Booking::create([
            'id_pelanggan'       => $pelanggan->id_pelanggan,
            'id_layanan'         => $layananDetails[0]['id_layanan'] ?? null,
            'tanggal_booking'    => $request->tanggal_booking,
            'waktu_booking'      => $request->waktu_booking,
            'estimasi_berat'     => $totalEstimasiBerat > 0 ? $totalEstimasiBerat : null,
            'catatan'            => $request->catatan,
            'tipe_antar_jemput'  => $request->tipe_antar_jemput,
            'alamat_jemput'      => $request->alamat_jemput,
            'alamat_antar'       => $request->alamat_antar,
            'biaya_antar_jemput' => 0,
            'dp_bayar'           => $dpBayar,
            'sisa_bayar'         => $sisaBayar,
            'status'             => 'pending',
            'metode_bayar'       => $request->metode_bayar,
            'bukti_pembayaran'   => $buktiPath,
        ]);

        foreach ($layananDetails as $detail) {
            \App\Models\BookingLayanan::create(array_merge(
                ['id_booking' => $booking->id_booking],
                $detail
            ));
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Booking berhasil dikirim! Kami akan segera mengkonfirmasi.');
    }

    public function detailTransaksi($id)
    {
        $pelanggan = auth()->user()->pelanggan;

        $transaksi = Transaksi::with([
            'detailTransaksi.layanan',
            'pewangi',
            'pengembalian',
            'pembayaran',
            'booking',
        ])->where('id_pelanggan', $pelanggan->id_pelanggan)
          ->where('id_transaksi', $id)
          ->firstOrFail();

        return view('customer.detail-transaksi', compact('transaksi', 'pelanggan'));
    }

    public function fakturPembayaran($id_transaksi)
    {
        $pelanggan = auth()->user()->pelanggan;

        $transaksi = Transaksi::with([
            'detailTransaksi.layanan',
            'pewangi',
            'pembayaran',
            'booking',
            'user',
            'pelanggan',
        ])->where('id_pelanggan', $pelanggan->id_pelanggan)
          ->where('id_transaksi', $id_transaksi)
          ->firstOrFail();

        if (!$transaksi->pembayaran) {
            return redirect()->route('customer.transaksi.detail', $id_transaksi)
                ->with('error', 'Belum ada data pembayaran untuk transaksi ini.');
        }

        return view('customer.faktur', compact('transaksi', 'pelanggan'));
    }

    public function cetakFaktur($id_transaksi)
    {
        $pelanggan = auth()->user()->pelanggan;

        $transaksi = Transaksi::with([
            'detailTransaksi.layanan',
            'pewangi',
            'pembayaran',
            'booking',
            'user',
            'pelanggan',
        ])->where('id_pelanggan', $pelanggan->id_pelanggan)
          ->where('id_transaksi', $id_transaksi)
          ->firstOrFail();

        if (!$transaksi->pembayaran) {
            return redirect()->route('customer.transaksi.faktur', $id_transaksi)
                ->with('error', 'Belum ada data pembayaran.');
        }

        return view('customer.faktur-print', compact('transaksi', 'pelanggan'));
    }
}
