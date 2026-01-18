<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder yang sudah ada sebelumnya
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seeder baru untuk admin dan answers
        $this->call([
            AdminSeeder::class,
            QuestionSeeder::class,
            AnswerSeeder::class,
        ]);
    }
}
