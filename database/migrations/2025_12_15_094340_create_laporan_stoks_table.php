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
        Schema::create('laporan_stoks', function (Blueprint $table) {
            $table->id('id_laporan_stok');
            $table->foreignId('id_barang')->constrained('stok_barangs', 'id_barang')->onDelete('cascade');
            $table->date('tanggal_laporan');
            $table->integer('stok_awal');
            $table->integer('stok_akhir');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_stoks');
    }
};
