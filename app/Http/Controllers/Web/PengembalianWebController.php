<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Transaksi;
use App\Models\Booking;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianWebController extends Controller
{
    public function __construct(protected WhatsAppService $whatsappService) {}

    public function index()
    {
        $pengembalians = Pengembalian::with(['transaksi.pelanggan', 'transaksi.user', 'transaksi.booking', 'transaksi.pembayaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.pengembalians.index', compact('pengembalians'));
    }

    public function create()
    {
        $transaksis = Transaksi::with(['pelanggan', 'pembayaran', 'booking'])
            ->where('status', 'selesai')
            ->whereDoesntHave('pengembalian')
            ->get();

        $bookings = Booking::with('pelanggan')->get();

        return view('admin.pengembalians.create', compact('transaksis', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_transaksi'         => 'required|exists:transaksis,id_transaksi',
            'id_booking'           => 'nullable|exists:bookings,id_booking',
            'tanggal_pengembalian' => 'required|date',
            'status_pengembalian'  => 'required|in:siap_diambil,sudah_diambil',
            'catatan'              => 'nullable|string',
            'kirim_notifikasi'     => 'nullable|boolean',
        ]);

        $transaksi = Transaksi::with(['pembayaran', 'booking'])->findOrFail($validated['id_transaksi']);

        $totalTagihan = $transaksi->total_harga + ($transaksi->booking?->biaya_antar_jemput ?? 0);
        $sudahBayar   = $transaksi->pembayaran && $transaksi->pembayaran->status_bayar === 'lunas';

        if (!$sudahBayar && $totalTagihan > 0) {
            return redirect()->back()
                ->with('error', 'Pembayaran belum lunas! Tagihan: Rp ' . number_format($totalTagihan, 0, ',', '.') . '. Silakan catat pembayaran terlebih dahulu.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::create([
                'id_transaksi'         => $validated['id_transaksi'],
                'id_booking'           => $validated['id_booking'] ?? $transaksi->id_booking ?? null,
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
                'status_pengembalian'  => $validated['status_pengembalian'],
                'catatan'              => $validated['catatan'] ?? null,
            ]);

            if ($request->boolean('kirim_notifikasi')) {
                $result = $this->whatsappService->sendPengembalianNotification($pengembalian);
                if ($result['success']) {
                    $pengembalian->update([
                        'notifikasi_terkirim' => true,
                        'tanggal_notifikasi'  => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.pengembalians.index')->with('success', 'Pengambilan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengembalians.create')->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $pengembalian = Pengembalian::with(['transaksi.pelanggan', 'transaksi.pembayaran', 'transaksi.booking'])->findOrFail($id);
        return view('admin.pengembalians.edit', compact('pengembalian'));
    }

    public function update(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $validated = $request->validate([
            'status_pengembalian' => 'required|in:siap_diambil,sudah_diambil',
            'catatan'             => 'nullable|string',
        ]);

        $pengembalian->update($validated);

        return redirect()->route('admin.pengembalians.index')->with('success', 'Pengambilan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();

        return redirect()->route('admin.pengembalians.index')->with('success', 'Pengambilan berhasil dihapus.');
    }

    public function resendNotification($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $result = $this->whatsappService->sendPengembalianNotification($pengembalian);

        if ($result['success']) {
            $pengembalian->update([
                'notifikasi_terkirim' => true,
                'tanggal_notifikasi'  => now(),
            ]);
        }

        return response()->json($result, $result['success'] ? 200 : 500);
    }
}
