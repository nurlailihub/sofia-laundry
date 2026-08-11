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
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id('id_pengembalian');
            $table->foreignId('id_transaksi')->constrained('transaksis', 'id_transaksi')->onDelete('cascade');
            $table->dateTime('tanggal_pengembalian');
            $table->enum('status_pengembalian', ['siap_diambil', 'sudah_diambil'])->default('siap_diambil');
            $table->text('catatan')->nullable();
            $table->boolean('notifikasi_terkirim')->default(false);
            $table->dateTime('tanggal_notifikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
