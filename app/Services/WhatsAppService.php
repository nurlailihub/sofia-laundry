<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Fonnte API endpoint
     */
    protected const API_URL = 'https://api.fonnte.com/send';

    protected string $token;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN', '');
    }

    /**
     * Send WhatsApp notification via Fonnte API
     *
     * @param string $phone Nomor HP (format bebas: 08xxx / 628xxx / +628xxx)
     * @param string $message Isi pesan
     * @return array{success: bool, message: string}
     */
    public function sendNotification(string $phone, string $message): array
    {
        try {
            $phone = $this->formatPhone($phone);

            if (!$phone) {
                return ['success' => false, 'message' => 'Format nomor HP tidak valid'];
            }

            if (empty($this->token)) {
                Log::warning('WhatsApp: FONNTE_TOKEN belum diset di .env');
                return ['success' => false, 'message' => 'Token Fonnte belum dikonfigurasi'];
            }

            $response = Http::withHeaders([
                    // Fonnte tidak pakai "Bearer", langsung token-nya
                    'Authorization' => $this->token,
                ])
                ->timeout(15)
                ->connectTimeout(5)
                ->post(self::API_URL, [
                    'target'      => $phone,
                    'message'     => $message,
                    // countryCode '0' = nonaktifkan filter fonnte,
                    // kita sudah format nomor jadi 62xxx di atas
                    'countryCode' => '0',
                ]);

            $data = $response->json();

            // Fonnte sukses: status === true
            if ($response->successful() && ($data['status'] ?? false) === true) {
                Log::info('WhatsApp Fonnte sent', ['phone' => $phone, 'id' => $data['id'] ?? null]);
                return ['success' => true, 'message' => 'Notifikasi WhatsApp berhasil dikirim'];
            }

            $reason = $data['reason'] ?? $data['detail'] ?? $response->body();
            Log::warning('WhatsApp Fonnte failed', ['phone' => $phone, 'reason' => $reason]);
            return ['success' => false, 'message' => $reason ?: 'Gagal mengirim pesan WhatsApp'];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('WhatsApp Fonnte connection error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Tidak bisa terhubung ke Fonnte API: ' . $e->getMessage()];
        } catch (\Exception $e) {
            Log::error('WhatsApp Fonnte error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error notifikasi: ' . $e->getMessage()];
        }
    }

    /**
     * Format nomor HP ke format 628xxx (tanpa +)
     * Return null jika nomor tidak valid
     */
    protected function formatPhone(string $phone): ?string
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Ganti awalan 0 jadi 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Tambah 62 jika belum ada
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        // Validasi panjang: 62 + 8-13 digit
        if (!preg_match('/^62[0-9]{8,13}$/', $phone)) {
            Log::warning('WhatsApp: Invalid phone number', ['phone' => $phone]);
            return null;
        }

        return $phone;
    }

    // =========================================================================
    // Template Notifications
    // =========================================================================

    /**
     * Kirim notifikasi pengembalian laundry ke pelanggan
     */
    public function sendPengembalianNotification($pengembalian): array
    {
        $transaksi = $pengembalian->transaksi;
        $pelanggan = $transaksi->pelanggan;

        if (!$pelanggan) {
            return ['success' => false, 'message' => 'Data pelanggan tidak ditemukan'];
        }

        $noHp = $pelanggan->no_hp ?? null;
        if (!$noHp) {
            return ['success' => false, 'message' => 'Nomor HP pelanggan tidak ditemukan'];
        }

        $message = $this->getPengembalianTemplate($pelanggan->nama_pelanggan, $transaksi);

        return $this->sendNotification($noHp, $message);
    }

    /**
     * Kirim notifikasi perubahan status transaksi ke pelanggan
     */
    public function sendStatusUpdateNotification($transaksi): array
    {
        $pelanggan = $transaksi->pelanggan;

        if (!$pelanggan || !$pelanggan->no_hp) {
            return ['success' => false, 'message' => 'Data pelanggan atau nomor HP tidak ditemukan'];
        }

        $message = $this->getStatusUpdateTemplate($pelanggan->nama_pelanggan, $transaksi);

        return $this->sendNotification($pelanggan->no_hp, $message);
    }

    // =========================================================================
    // Private Template Builders
    // =========================================================================

    private function getPengembalianTemplate(string $namaPelanggan, $transaksi): string
    {
        $tanggal = \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y');

        return "🧺 *LAUNDRY SIAP DIAMBIL* 🧺\n\n" .
               "Halo *{$namaPelanggan}*,\n\n" .
               "Laundry Anda sudah selesai dan siap untuk diambil!\n\n" .
               "📋 *Detail:*\n" .
               "• Tanggal Selesai: {$tanggal}\n" .
               "• Total Berat: " . number_format($transaksi->total_berat, 2) . " Kg\n" .
               "• Total Harga: Rp " . number_format($transaksi->total_harga, 0, ',', '.') . "\n" .
               ($transaksi->pewangi ? "• Pewangi: " . $transaksi->pewangi->nama_barang . "\n" : "") . "\n" .
               "Terima kasih telah menggunakan layanan kami! 🙏";
    }

    private function getStatusUpdateTemplate(string $namaPelanggan, $transaksi): string
    {
        $statusLabels = \App\Models\Transaksi::$statusDetailLabels;
        $statusLabel  = $statusLabels[$transaksi->status_detail] ?? $transaksi->status_detail;
        $noTransaksi  = '#' . str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT);

        $templates = [
            'menunggu' => "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} telah *diterima* dan sedang menunggu untuk diproses.\n\n" .
                "Kami akan menginformasikan perkembangan selanjutnya. Terima kasih! 🙏",

            'diterima' => "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Cucian Anda dengan nomor {$noTransaksi} telah *diterima* oleh admin.\n\n" .
                "Terima kasih! 🙏",

            'dijemput' => "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} sedang *dijemput* oleh admin.\n\n" .
                "Mohon persiapkan cucian Anda. Terima kasih! 🙏",

            'sedang_dicuci' => "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} *sedang dicuci*.\n\n" .
                "Kami akan menginformasikan jika sudah selesai. Terima kasih! 🙏",

            'sedang_dikeringkan' => "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} *sedang dikeringkan*.\n\n" .
                "Terima kasih! 🙏",

            'sedang_disetrika' => "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} *sedang disetrika*.\n\n" .
                "Sebentar lagi selesai! Terima kasih! 🙏",

            'sedang_dikemas' => "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} *sedang dikemas*.\n\n" .
                "Siap-siap untuk diambil! Terima kasih! 🙏",

            'siap_diambil' => "✅ *LAUNDRY SIAP DIAMBIL* ✅\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} *sudah siap untuk diambil*!\n\n" .
                "📋 *Ringkasan:*\n" .
                "• Total Berat: " . number_format($transaksi->total_berat, 2) . " Kg\n" .
                "• Total Harga: Rp " . number_format($transaksi->total_harga, 0, ',', '.') . "\n" .
                ($transaksi->tanggal_selesai ? "• Estimasi Selesai: " . \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') . "\n" : "") .
                ($transaksi->pewangi ? "• Pewangi: " . $transaksi->pewangi->nama_barang . "\n" : "") . "\n" .
                "Silakan datang ke tempat kami untuk mengambilnya. Terima kasih! 🙏",

            'selesai' => "🎉 *LAUNDRY SELESAI* 🎉\n\n" .
                "Halo *{$namaPelanggan}*,\n\n" .
                "Laundry Anda dengan nomor {$noTransaksi} *sudah selesai dan sudah diambil*.\n\n" .
                "Terima kasih telah menggunakan layanan kami! Sampai jumpa lagi! 🧺🙏",
        ];

        $message = $templates[$transaksi->status_detail]
            ?? "📋 *UPDATE STATUS LAUNDRY* 📋\n\n" .
               "Halo *{$namaPelanggan}*,\n\n" .
               "Status Laundry Anda dengan nomor {$noTransaksi} telah diperbarui menjadi: *{$statusLabel}*.\n\n" .
               "Terima kasih! 🙏";

        if ($transaksi->catatan_status) {
            $message .= "\n\n📝 *Catatan:* {$transaksi->catatan_status}";
        }

        return $message;
    }
}
