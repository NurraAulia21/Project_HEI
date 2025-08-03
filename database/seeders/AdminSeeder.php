<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            'username' => 'admin',
            'name'     => 'Super Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'is_active'=> true,
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
