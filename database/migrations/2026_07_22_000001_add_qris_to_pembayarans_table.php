<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN metode_bayar ENUM('cash','transfer','qris') NOT NULL");

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('nomor_referensi', 100)->nullable()->after('jumlah_bayar');
            $table->text('catatan')->nullable()->after('nomor_referensi');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['nomor_referensi', 'catatan']);
        });
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN metode_bayar ENUM('cash','transfer') NOT NULL");
    }
};
