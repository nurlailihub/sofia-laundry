<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('dp_bayar', 12, 2)->nullable()->after('biaya_antar_jemput');
            $table->decimal('sisa_bayar', 12, 2)->nullable()->after('dp_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['dp_bayar', 'sisa_bayar']);
        });
    }
};
