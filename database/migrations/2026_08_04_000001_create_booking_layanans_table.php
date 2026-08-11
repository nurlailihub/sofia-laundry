<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_layanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_booking');
            $table->unsignedBigInteger('id_layanan');
            $table->decimal('estimasi_berat', 8, 2)->nullable();
            $table->decimal('estimasi_subtotal', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_booking')->references('id_booking')->on('bookings')->onDelete('cascade');
            $table->foreign('id_layanan')->references('id_layanan')->on('layanans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_layanans');
    }
};
