<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('layanans')
            ->where('nama_layanan', 'Cuci Gosok')
            ->update(['nama_layanan' => 'Cuci Setrika']);
    }

    public function down(): void
    {
        DB::table('layanans')
            ->where('nama_layanan', 'Cuci Setrika')
            ->update(['nama_layanan' => 'Cuci Gosok']);
    }
};
