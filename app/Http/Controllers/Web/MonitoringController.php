<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        $transaksis = Transaksi::with(['pelanggan', 'detailTransaksi.layanan', 'pewangi'])
            ->whereNotIn('status', ['diambil'])
            ->orderBy('created_at', 'asc')
            ->get();

        $grouped = $transaksis->groupBy('status_detail');

        $statusList   = Transaksi::$statusDetailLabels;
        $statusIcons  = Transaksi::$statusDetailIcons;
        $statusColors = Transaksi::$statusDetailColors;

        return view('admin.monitoring.index', compact('transaksis', 'grouped', 'statusList', 'statusIcons', 'statusColors'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_detail'  => 'required|in:' . implode(',', array_keys(Transaksi::$statusDetailLabels)),
            'catatan_status' => 'nullable|string|max:255',
            'kirim_wa'       => 'nullable|boolean',
        ]);

        try {
            $transaksi = Transaksi::with(['pelanggan', 'pewangi'])->findOrFail($id);
            $transaksi->status_detail  = $request->status_detail;
            $transaksi->catatan_status = $request->catatan_status;

            if ($request->status_detail === 'selesai') {
                $transaksi->status = 'diambil';
            } elseif ($request->status_detail === 'siap_diambil') {
                $transaksi->status = 'selesai';
            } else {
                $transaksi->status = 'proses';
            }

            $transaksi->save();

            // Kirim WA — tangkap exception agar tidak mempengaruhi response
            $notifResult = null;
            if ($request->boolean('kirim_wa')) {
                try {
                    $notifResult = $this->whatsappService->sendStatusUpdateNotification($transaksi);
                } catch (\Exception $waEx) {
                    \Illuminate\Support\Facades\Log::warning('WA notification error: ' . $waEx->getMessage());
                    $notifResult = [
                        'success' => false,
                        'message' => 'Gagal mengirim notifikasi: ' . $waEx->getMessage(),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'label'   => Transaksi::$statusDetailLabels[$request->status_detail],
                'notif'   => $notifResult,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // biarkan Laravel handle validation error

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('updateStatus error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
