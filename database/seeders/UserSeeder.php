<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin123@gmail.com'], // Kunci untuk mencari pengguna
            [
                'name' => 'Admin Utama', // Data untuk dibuat atau diperbarui
                'password' => Hash::make('admin123'),
                'role' => 'admin', // Pastikan role admin utama diatur
                'avatar' => hash('sha256', strtolower(trim('admin123@gmail.com')))
            ]
        );
    }
}
