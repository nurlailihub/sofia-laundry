<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('tipe_antar_jemput', ['none', 'pickup', 'delivery', 'both'])
                  ->default('none')
                  ->after('catatan_status');
            $table->decimal('biaya_antar_jemput', 12, 2)
                  ->default(0)
                  ->after('tipe_antar_jemput');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['tipe_antar_jemput', 'biaya_antar_jemput']);
        });
    }
};
