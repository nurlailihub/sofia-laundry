<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('kode_reservasi', 20)->unique()->after('id_booking');
        });

        $bookings = DB::table('bookings')->orderBy('id_booking')->get();
        foreach ($bookings as $booking) {
            DB::table('bookings')
                ->where('id_booking', $booking->id_booking)
                ->update(['kode_reservasi' => 'RSV-' . str_pad($booking->id_booking, 4, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('kode_reservasi');
        });
    }
};
