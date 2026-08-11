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
        Schema::table('users', function (Blueprint $table) {
            // Rename columns
            $table->renameColumn('id', 'id_user');
            $table->renameColumn('name', 'nama_user');
            
            // Drop email columns
            $table->dropColumn(['email', 'email_verified_at']);
            
            // Add new columns
            $table->string('username', 50)->unique()->after('nama_user');
            $table->enum('role', ['admin', 'pimpinan'])->default('admin')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('id_user', 'id');
            $table->renameColumn('nama_user', 'name');
            
            // Add back email columns
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            
            // Drop added columns
            $table->dropColumn(['username', 'role']);
        });
    }
};
