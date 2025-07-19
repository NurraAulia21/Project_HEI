<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question_text' => 'Saya lebih suka menghabiskan waktu dengan sekelompok kecil teman dekat daripada di pesta besar',
                'category' => 'I',
                'order' => 1,
                'is_active' => true
            ],
            [
                'question_text' => 'Saya merasa berenergi setelah berinteraksi dengan banyak orang',
                'category' => 'H',
                'order' => 2,
                'is_active' => true
            ],
            [
                'question_text' => 'Saya lebih fokus pada detail-detail kecil daripada gambaran besar',
                'category' => 'I',
                'order' => 3,
                'is_active' => true
            ],
            [
                'question_text' => 'Saya sering bermimpi tentang kemungkinan-kemungkinan masa depan',
                'category' => 'E',
                'order' => 4,
                'is_active' => true
            ],
            [
                'question_text' => 'Ketika membuat keputusan, saya lebih mengandalkan logika daripada perasaan',
                'category' => 'I',
                'order' => 5,
                'is_active' => true
            ]
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}