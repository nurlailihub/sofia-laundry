<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'nama_user' => 'Administrator',
            'username' => 'admin',
            'password' => bcrypt('admin123'), // Password: admin123
            'role' => 'admin',
        ]);

        // Create Pimpinan User
        User::create([
            'nama_user' => 'Pimpinan Laundry',
            'username' => 'pimpinan',
            'password' => bcrypt('pimpinan123'), // Password: pimpinan123
            'role' => 'pimpinan',
        ]);
    }
}
