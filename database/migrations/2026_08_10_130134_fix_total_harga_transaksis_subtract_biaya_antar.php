<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Koreksi data: total_harga sebelumnya menyimpan subtotal layanan + biaya_antar_jemput.
 * Seharusnya total_harga = subtotal layanan saja, biaya antar sudah ada di kolom terpisah.
 * Migration ini mengurangi total_harga dengan biaya_antar_jemput untuk data yang terpengaruh.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Kurangi total_harga dengan biaya_antar_jemput untuk semua transaksi
        // yang biaya_antar_jemput > 0 (artinya total_harga masih include biaya antar)
        DB::statement('
            UPDATE transaksis
            SET total_harga = total_harga - biaya_antar_jemput
            WHERE biaya_antar_jemput > 0
        ');
    }

    public function down(): void
    {
        // Rollback: tambahkan kembali biaya_antar_jemput ke total_harga
        DB::statement('
            UPDATE transaksis
            SET total_harga = total_harga + biaya_antar_jemput
            WHERE biaya_antar_jemput > 0
        ');
    }
};
