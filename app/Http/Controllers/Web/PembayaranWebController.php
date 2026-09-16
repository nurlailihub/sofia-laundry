<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class PembayaranWebController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsappService
    ) {}

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

        // ── Kirim struk WA otomatis ke pelanggan ────────────────────────
        $kirimWA = $this->doKirimStrukWA($pembayaran);
        $waInfo  = $kirimWA['success']
            ? '✅ Struk telah dikirim ke WhatsApp pelanggan.'
            : '⚠️ Struk WA tidak terkirim: ' . $kirimWA['message'];

        return redirect()->route('admin.pembayarans.faktur', $pembayaran->id_pembayaran)
            ->with('success', 'Pembayaran berhasil dicatat.')
            ->with($kirimWA['success'] ? 'wa_success' : 'wa_error', $waInfo);
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

        // Generate signed URL untuk struk publik (berlaku 30 hari)
        $linkStruk = URL::signedRoute('struk.public', ['id' => $pembayaran->id_pembayaran], now()->addDays(30));

        return view('admin.pembayarans.faktur', compact('pembayaran', 'tipeFaktur', 'linkStruk'));
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
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view)->setPaper([0, 0, 226.77, 600], 'portrait');
            return $pdf->download('struk-' . $pembayaran->nomor_faktur . '.pdf');
        }

        return view('admin.pembayarans.faktur-print', compact('pembayaran', 'tipeFaktur'));
    }

    /**
     * Kirim struk WA secara manual (tombol di halaman faktur)
     */
    public function kirimStrukWA($id)
    {
        $pembayaran = Pembayaran::with([
            'transaksi.pelanggan',
            'transaksi.detailTransaksi.layanan',
            'transaksi.pewangi',
            'transaksi.user',
            'transaksi.booking',
        ])->findOrFail($id);

        $result = $this->doKirimStrukWA($pembayaran);

        if ($result['success']) {
            return redirect()->back()->with('wa_success', '✅ ' . $result['message']);
        }

        return redirect()->back()->with('wa_error', '❌ ' . $result['message']);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('admin.pembayarans.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * Helper internal — generate link struk + panggil WhatsAppService.
     *
     * Link yang dikirim ke WA menggunakan WA_PUBLIC_URL dari .env,
     * bukan APP_URL, sehingga bisa dikonfigurasi terpisah untuk
     * domain publik (ngrok/server production) sementara APP_URL
     * tetap untuk kebutuhan lokal.
     */
    private function doKirimStrukWA(Pembayaran $pembayaran): array
    {
        try {
            // Load relasi yang diperlukan jika belum
            $pembayaran->loadMissing([
                'transaksi.pelanggan',
                'transaksi.detailTransaksi.layanan',
                'transaksi.pewangi',
                'transaksi.booking',
            ]);

            // Generate signed URL menggunakan WA_PUBLIC_URL
            // sehingga link yang dikirim ke pelanggan bisa diakses dari HP mereka
            $linkStruk = $this->buildPublicStrukUrl($pembayaran->id_pembayaran);

            return $this->whatsappService->sendStrukPembayaran($pembayaran, $linkStruk);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('doKirimStrukWA error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Generate signed URL struk dengan base domain dari WA_PUBLIC_URL.
     * Jika WA_PUBLIC_URL tidak diset, fallback ke URL::signedRoute() biasa.
     */
    private function buildPublicStrukUrl(int $pembayaranId): string
    {
        $publicBase = rtrim(env('WA_PUBLIC_URL', ''), '/');

        if (empty($publicBase)) {
            // Fallback: pakai APP_URL bawaan Laravel
            return URL::signedRoute(
                'struk.public',
                ['id' => $pembayaranId],
                now()->addDays(30)
            );
        }

        // Generate signed URL dulu pakai APP_URL, lalu ganti base-nya
        // dengan WA_PUBLIC_URL agar signature tetap valid
        $originalAppUrl = config('app.url');

        // Override APP_URL sementara untuk generate URL dengan domain publik
        \Illuminate\Support\Facades\URL::forceRootUrl($publicBase);

        $signedUrl = URL::signedRoute(
            'struk.public',
            ['id' => $pembayaranId],
            now()->addDays(30)
        );

        // Kembalikan root URL ke semula
        \Illuminate\Support\Facades\URL::forceRootUrl($originalAppUrl);

        return $signedUrl;
    }
}
