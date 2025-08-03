<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin utama
        Admin::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@hei-assessment.com',
            'password' => 'admin123', // akan di-hash otomatis oleh mutator
            'is_active' => true,
        ]);

        // Admin kedua untuk testing
        Admin::create([
            'username' => 'admin2',
            'name' => 'Admin Kedua',
            'email' => 'admin2@hei-assessment.com', 
            'password' => 'admin456',
            'is_active' => true,
        ]);

        // Admin yang tidak aktif untuk testing
        Admin::create([
            'username' => 'admin_inactive',
            'name' => 'Admin Tidak Aktif',
            'email' => 'inactive@hei-assessment.com',
            'password' => 'inactive123',
            'is_active' => false,
        ]);
    }
}