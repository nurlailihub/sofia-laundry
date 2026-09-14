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
        // Create or update Admin User
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_user' => 'Administrator',
                'password'  => bcrypt('admin123'),
                'role'      => 'admin',
            ]
        );

        // Create or update Pimpinan User
        User::updateOrCreate(
            ['username' => 'pimpinan'],
            [
                'nama_user' => 'Pimpinan Laundry',
                'password'  => bcrypt('pimpinan123'),
                'role'      => 'pimpinan',
            ]
        );
    }
}
