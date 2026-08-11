<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom id_pewangi sebagai foreign key ke stok_barangs
            $table->foreignId('id_pewangi')
                ->nullable()
                ->after('id_user')
                ->constrained('stok_barangs', 'id_barang')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menghapus foreign key dan kolom id_pewangi
            $table->dropForeign(['id_pewangi']);
            $table->dropColumn('id_pewangi');
        });
    }
};
