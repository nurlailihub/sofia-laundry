<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status_detail', [
                'menunggu',
                'diterima',
                'sedang_dicuci',
                'sedang_dikeringkan',
                'sedang_disetrika',
                'sedang_dikemas',
                'siap_diambil',
                'selesai',
            ])->default('menunggu')->after('status');
            $table->text('catatan_status')->nullable()->after('status_detail');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['status_detail', 'catatan_status']);
        });
    }
};
