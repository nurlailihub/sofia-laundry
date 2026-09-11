<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Observer yang me-reset AUTO_INCREMENT suatu tabel setelah data dihapus,
 * sehingga ID berikutnya akan meneruskan dari angka tertinggi yang ada + 1.
 * Ini mencegah ID "loncat" jauh setelah banyak data dihapus.
 *
 * Cara kerja:
 * - Setelah delete, cari MAX(primary_key) di tabel.
 * - Jalankan ALTER TABLE ... AUTO_INCREMENT = MAX + 1.
 * - MySQL tidak akan turunkan AUTO_INCREMENT di bawah MAX + 1 yang sudah ada,
 *   jadi operasi ini aman terhadap data yang masih ada.
 */
class AutoIncrementResetObserver
{
    /**
     * Dipanggil setelah sebuah record berhasil dihapus.
     */
    public function deleted(Model $model): void
    {
        $table   = $model->getTable();
        $pkCol   = $model->getKeyName();

        // Ambil nilai MAX id yang tersisa
        $maxId = (int) DB::table($table)->max($pkCol);

        // Set AUTO_INCREMENT ke max + 1 (minimal 1 jika tabel kosong)
        $next = max(1, $maxId + 1);

        DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$next}");
    }
}
