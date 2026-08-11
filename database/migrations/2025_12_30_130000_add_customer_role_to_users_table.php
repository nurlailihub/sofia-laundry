<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum role untuk menambahkan 'customer'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pimpinan', 'customer') DEFAULT 'admin'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum sebelumnya (hapus customer)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pimpinan') DEFAULT 'admin'");
    }
};

