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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_layanan');
            $table->date('tanggal_booking');
            $table->time('waktu_booking')->nullable();
            $table->decimal('estimasi_berat', 8, 2)->nullable();
            $table->text('catatan')->nullable();
            // tipe_antar_jemput: none (tidak antar jemput), pickup (jemput), delivery (antar), both (jemput & antar)
            $table->enum('tipe_antar_jemput', ['none', 'pickup', 'delivery', 'both'])->default('none');
            $table->text('alamat_jemput')->nullable();
            $table->text('alamat_antar')->nullable();
            $table->decimal('biaya_antar_jemput', 10, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('cascade');
            $table->foreign('id_layanan')->references('id_layanan')->on('layanans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};


