<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * Controller untuk menampilkan struk pembayaran secara publik
 * menggunakan signed URL (tidak butuh login).
 * URL ini yang dibagikan ke pelanggan via WhatsApp.
 */
class StrukPublicController extends Controller
{
    /**
     * Tampilkan struk pembayaran via signed URL.
     * URL otomatis kadaluwarsa setelah 30 hari.
     */
    public function show(Request $request, $id)
    {
        // Validasi signature — tolak akses jika URL dimanipulasi
        if (! $request->hasValidSignature()) {
            abort(403, 'Link struk tidak valid atau sudah kadaluwarsa.');
        }

        $pembayaran = Pembayaran::with([
            'transaksi.pelanggan',
            'transaksi.detailTransaksi.layanan',
            'transaksi.pewangi',
            'transaksi.user',
            'transaksi.booking',
        ])->findOrFail($id);

        $tipeLabel = ['none' => 'Sendiri', 'pickup' => 'Dijemput', 'delivery' => 'Diantar', 'both' => 'Jemput & Antar'];

        return view('struk.public', compact('pembayaran', 'tipeLabel'));
    }
}
