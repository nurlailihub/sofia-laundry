<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status_detail ENUM(
            'menunggu',
            'diterima',
            'dijemput',
            'sedang_dicuci',
            'sedang_dikeringkan',
            'sedang_disetrika',
            'sedang_dikemas',
            'siap_diambil',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN status_detail ENUM(
            'menunggu',
            'diterima',
            'sedang_dicuci',
            'sedang_dikeringkan',
            'sedang_disetrika',
            'sedang_dikemas',
            'siap_diambil',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu'");
    }
};
