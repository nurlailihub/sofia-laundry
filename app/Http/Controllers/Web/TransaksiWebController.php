<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\DetailTransaksi;
use App\Models\StokBarang;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiWebController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        $transaksis   = Transaksi::with(['pelanggan', 'user', 'pewangi', 'pembayaran', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        $pelanggans   = Pelanggan::all();
        $layanans     = Layanan::all();
        $stok_barangs = StokBarang::all();

        return view('admin.transaksis.index', compact('transaksis', 'pelanggans', 'layanans', 'stok_barangs'));
    }

    public function create()
    {
        $pelanggans   = Pelanggan::all();
        $layanans     = Layanan::all();
        $stok_barangs = StokBarang::all();

        return view('admin.transaksis.create', compact('pelanggans', 'layanans', 'stok_barangs'));
    }

    public function edit($id)
    {
        $transaksi    = Transaksi::with(['detailTransaksi.layanan', 'pelanggan'])->findOrFail($id);
        $pelanggans   = Pelanggan::all();
        $layanans     = Layanan::all();
        $stok_barangs = StokBarang::all();

        return view('admin.transaksis.edit', compact('transaksi', 'pelanggans', 'layanans', 'stok_barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan'             => 'required|exists:pelanggans,id_pelanggan',
            'id_pewangi'               => 'nullable|exists:stok_barangs,id_barang',
            'tanggal_masuk'            => 'required|date',
            'tanggal_selesai'          => 'nullable|date',
            'total_berat'              => 'required|numeric|min:0',
            'total_harga'              => 'required|numeric|min:0',
            'tipe_antar_jemput'        => 'nullable|in:none,pickup,delivery,both',
            'biaya_antar_jemput'       => 'nullable|numeric|min:0',
            'alamat_jemput'            => 'required_if:tipe_antar_jemput,pickup,both|nullable|string|max:500',
            'alamat_antar'             => 'required_if:tipe_antar_jemput,delivery,both|nullable|string|max:500',
            'status'                   => 'required|in:proses,selesai,diambil',
            'details'                  => 'required|array',
            'details.*.id_layanan'     => 'required|exists:layanans,id_layanan',
            'details.*.berat'          => 'required|numeric|min:0',
            'details.*.subtotal'       => 'required|numeric|min:0',
        ]);

        $tipeAntar       = $request->tipe_antar_jemput ?? 'none';
        $biayaAntar      = ($tipeAntar !== 'none') ? (float)($request->biaya_antar_jemput ?? 0) : 0;
        $subtotalLayanan = (float)$validated['total_harga'] - $biayaAntar;

        DB::beginTransaction();
        try {
            if ($request->id_pewangi) {
                $pewangi = StokBarang::findOrFail($request->id_pewangi);
                if ($pewangi->stok < 1) {
                    DB::rollBack();
                    return redirect()->route('admin.transaksis.create')
                        ->with('error', 'Stok pewangi tidak mencukupi.')
                        ->withInput();
                }
            }

            $transaksi = Transaksi::create([
                'id_pelanggan'       => $validated['id_pelanggan'],
                'id_user'            => auth()->id(),
                'id_pewangi'         => $validated['id_pewangi'] ?? null,
                'tanggal_masuk'      => $validated['tanggal_masuk'],
                'tanggal_selesai'    => $validated['tanggal_selesai'] ?? null,
                'total_berat'        => $validated['total_berat'],
                'total_harga'        => $subtotalLayanan,
                'tipe_antar_jemput'  => $tipeAntar,
                'biaya_antar_jemput' => $biayaAntar,
                'status'             => $validated['status'],
            ]);

            foreach ($request->details as $detail) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_layanan'   => $detail['id_layanan'],
                    'berat'        => $detail['berat'],
                    'subtotal'     => $detail['subtotal'],
                ]);
            }

            if ($request->id_pewangi) {
                $pewangi->decrement('stok', 1);
            }

            DB::commit();

            return redirect()->route('admin.transaksis.index')
                ->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.transaksis.create')
                ->with('error', 'Gagal: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::with('pelanggan')->findOrFail($id);

        $validated = $request->validate([
            'tanggal_selesai' => 'nullable|date',
            'status'          => 'required|in:proses,selesai,diambil',
            'id_pewangi'      => 'nullable|exists:stok_barangs,id_barang',
            'kirim_wa'        => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($request->has('id_pewangi') && $request->id_pewangi != $transaksi->id_pewangi) {
                if ($transaksi->id_pewangi) {
                    $pewangiLama = StokBarang::find($transaksi->id_pewangi);
                    if ($pewangiLama) {
                        $pewangiLama->increment('stok', 1);
                    }
                }
                if ($request->id_pewangi) {
                    $pewangiBaru = StokBarang::findOrFail($request->id_pewangi);
                    if ($pewangiBaru->stok < 1) {
                        DB::rollBack();
                        return redirect()->route('admin.transaksis.index')
                            ->with('error', 'Stok pewangi baru tidak mencukupi.');
                    }
                    $pewangiBaru->decrement('stok', 1);
                }
                $transaksi->id_pewangi = $request->id_pewangi;
            }

            $transaksi->tanggal_selesai = $validated['tanggal_selesai'] ?? null;
            $transaksi->status          = $validated['status'];
            $transaksi->save();

            DB::commit();

            if ($request->boolean('kirim_wa')) {
                $this->whatsappService->sendStatusUpdateNotification($transaksi);
            }

            return redirect()->route('admin.transaksis.index')
                ->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.transaksis.index')
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->id_pewangi) {
            $pewangi = StokBarang::find($transaksi->id_pewangi);
            if ($pewangi) {
                $pewangi->increment('stok', 1);
            }
        }

        $transaksi->delete();

        return redirect()->route('admin.transaksis.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function faktur($id)
    {
        $transaksi = Transaksi::with([
            'pelanggan',
            'detailTransaksi.layanan',
            'pewangi',
            'pembayaran',
            'booking',
            'user',
        ])->findOrFail($id);

        return view('admin.transaksis.faktur', compact('transaksi'));
    }

    public function cetakFaktur($id)
    {
        $transaksi = Transaksi::with([
            'pelanggan',
            'detailTransaksi.layanan',
            'pewangi',
            'pembayaran',
            'booking',
            'user',
        ])->findOrFail($id);

        $view = view('admin.transaksis.faktur-print', compact('transaksi'))->render();

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view)->setPaper('a5', 'portrait');
            return $pdf->download('faktur-transaksi-' . str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) . '.pdf');
        }

        return view('admin.transaksis.faktur-print', compact('transaksi'));
    }
}
