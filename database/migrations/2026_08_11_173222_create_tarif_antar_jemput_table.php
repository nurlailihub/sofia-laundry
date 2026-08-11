<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_antar_jemput', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['pickup', 'delivery', 'both'])->unique();
            $table->string('label');
            $table->decimal('harga', 10, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('tarif_antar_jemput')->insert([
            ['tipe' => 'pickup',   'label' => 'Dijemput Admin',  'harga' => 10000, 'keterangan' => 'Admin menjemput cucian ke lokasi pelanggan', 'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'delivery', 'label' => 'Diantar Admin',   'harga' => 10000, 'keterangan' => 'Cucian diantar kembali ke lokasi pelanggan',  'created_at' => now(), 'updated_at' => now()],
            ['tipe' => 'both',     'label' => 'Jemput & Antar',  'harga' => 18000, 'keterangan' => 'Jemput sekaligus antar cucian',               'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_antar_jemput');
    }
};
