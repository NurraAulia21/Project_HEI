<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; 
use App\Models\Answer;
use App\Models\User;
use App\Models\Question;

class AnswerSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada pertanyaan aktif
        $questions = Question::active()->get();
        
        if ($questions->isEmpty()) {
            $this->command->info('Tidak ada pertanyaan aktif untuk membuat data jawaban dummy.');
            return;
        }

        // Ambil semua user yang ada
        $users = User::all();
        
        // Buat dummy user jika belum ada atau kurang dari 3
        if ($users->count() < 3) {
            $this->command->info('Membuat user dummy untuk testing...');
            
            // Hanya buat user yang belum ada
            User::firstOrCreate(
                ['email' => 'student1@telkomuniversity.ac.id'],
                [
                    'username' => 'student1',
                    'name' => 'Mahasiswa Satu',
                    'password' => bcrypt('password123'),
                ]
            );

            User::firstOrCreate(
                ['email' => 'student2@telkomuniversity.ac.id'],
                [
                    'username' => 'student2', 
                    'name' => 'Mahasiswa Dua',
                    'password' => bcrypt('password123'),
                ]
            );

            User::firstOrCreate(
                ['email' => 'student3@telkomuniversity.ac.id'],
                [
                    'username' => 'student3',
                    'name' => 'Mahasiswa Tiga', 
                    'password' => bcrypt('password123'),
                ]
            );

            // Refresh users collection setelah membuat user baru
            $users = User::all();
        }

        // Sekarang users pasti ada, lanjut generate jawaban
        foreach ($users->take(3) as $user) {
            foreach ($questions->take(10) as $question) {
                // Skip jika sudah ada jawaban untuk kombinasi user-question-attempt ini
                if (Answer::where('user_id', $user->id)
                          ->where('question_id', $question->id)
                          ->where('attempt', 1)
                          ->exists()) {
                    continue;
                }

                // Generate jawaban random berdasarkan kategori pertanyaan
                $answerValue = $this->generateAnswerByCategory($question->category);

                Answer::create([
                    'user_id' => $user->id,
                    'question_id' => $question->id,
                    'answer_value' => $answerValue,
                    'attempt' => 1,
                    'answered_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }

        $this->command->info('Data jawaban dummy berhasil dibuat!');
    }

    private function generateAnswerByCategory($category)
    {
        switch ($category) {
            case 'H': return rand(3, 5);
            case 'E': return rand(2, 5);
            case 'I': return rand(3, 4);
            default: return rand(1, 5);
        }
    }
}