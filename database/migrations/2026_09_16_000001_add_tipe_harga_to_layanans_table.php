<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            // 'kg'     = harga per kilogram (default — layanan cuci)
            // 'satuan' = harga per pcs/item (sprei, selimut, dll.)
            $table->enum('tipe_harga', ['kg', 'satuan'])
                  ->default('kg')
                  ->after('harga_per_kg')
                  ->comment('kg = per kilogram, satuan = per pcs/item');
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn('tipe_harga');
        });
    }
};
