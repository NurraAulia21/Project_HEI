<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;

class TestController extends Controller
{
    public function index()
    {
        // Ambil pertanyaan yang aktif
        $questions = Question::where('is_active', true)->orderBy('order')->get();

        return view('test', compact('questions'));
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|in:1,2,3,4,5' // 1=Strongly Disagree, 5=Strongly Agree
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Answer submitted successfully'
        ]);
    }

    /**
     * Show test result for specific user and attempt
     */
    public function showResult($userId, $attempt = 1)
    {
        // Cari user berdasarkan ID
        $user = User::find($userId);
        
        // if (!$user) {
        //     return redirect()->route('test')->with('error', 'User tidak ditemukan');
        // }

        // Ambil jawaban user untuk attempt tertentu
        $answers = Answer::where('user_id', $userId)
                        ->where('attempt', $attempt)
                        ->with(['question'])
                        ->orderBy('created_at', 'asc')
                        ->get();

        // if ($answers->isEmpty()) {
        //     return redirect()->route('test')->with('error', 'Tidak ada data hasil test untuk user ini');
        // }

        // Hitung statistik per kategori
        $categoryResults = $this->calculateCategoryResults($answers);
        
        // Tentukan kepribadian dominan
        $dominantPersonality = $this->getDominantPersonality($categoryResults);
        
        // Ambil semua attempts yang tersedia untuk user ini
        $availableAttempts = Answer::where('user_id', $userId)
                                  ->select('attempt')
                                  ->distinct()
                                  ->orderBy('attempt')
                                  ->pluck('attempt');

        // Data untuk view
        $data = [
            'user' => $user,
            'answers' => $answers,
            'attempt' => $attempt,
            'availableAttempts' => $availableAttempts,
            'categoryResults' => $categoryResults,
            'dominantPersonality' => $dominantPersonality,
            'personalityDescription' => $this->getPersonalityDescription($dominantPersonality),
            'totalQuestions' => $answers->count(),
            'completionDate' => $answers->first()->created_at,
            'averageScore' => round($answers->avg('answer_value'), 2),
            'answerDistribution' => $this->getAnswerDistribution($answers),
        ];

        return view('test-result', $data);
    }

    /**
     * Calculate results per category
     */
    private function calculateCategoryResults($answers)
    {
        $categories = ['H', 'E', 'I'];
        $results = [];

        foreach ($categories as $category) {
            $categoryAnswers = $answers->filter(function ($answer) use ($category) {
                return $answer->question->category === $category;
            });

            $average = $categoryAnswers->avg('answer_value');
            $count = $categoryAnswers->count();

            $results[$category] = [
                'name' => $this->getCategoryName($category),
                'average' => $average ? round($average, 2) : 0,
                'count' => $count,
                'total_score' => $categoryAnswers->sum('answer_value'),
                'percentage' => $count > 0 ? round(($average / 5) * 100, 1) : 0,
                'level' => $this->getScoreLevel($average),
            ];
        }

        return $results;
    }

    /**
     * Get dominant personality based on highest average
     */
    private function getDominantPersonality($categoryResults)
    {
        $highest = collect($categoryResults)->sortByDesc('average');
        
        if ($highest->isEmpty()) {
            return 'H'; // Default
        }

        return $highest->keys()->first();
    }

    /**
     * Get category name
     */
    private function getCategoryName($category)
    {
        $names = [
            'H' => 'Harmony',
            'E' => 'Excellence', 
            'I' => 'Integrity'
        ];

        return $names[$category] ?? 'Unknown';
    }

    /**
     * Get score level description
     */
    private function getScoreLevel($average)
    {
        if ($average >= 4.5) return 'Sangat Tinggi';
        if ($average >= 4.0) return 'Tinggi';
        if ($average >= 3.5) return 'Cukup Tinggi';
        if ($average >= 3.0) return 'Sedang';
        if ($average >= 2.5) return 'Cukup Rendah';
        if ($average >= 2.0) return 'Rendah';
        return 'Sangat Rendah';
    }

    /**
     * Get personality description
     */
    private function getPersonalityDescription($personality)
    {
        $descriptions = [
            'H' => [
                'title' => 'Harmony (Keharmonisan)',
                'description' => 'Anda adalah orang yang mengutamakan keharmonisan dalam hubungan interpersonal. Anda cenderung kooperatif, empati tinggi, dan lebih suka bekerja dalam tim daripada sendiri.',
                'characteristics' => [
                    'Mudah beradaptasi dengan lingkungan',
                    'Menghindari konflik dan mencari solusi win-win',
                    'Peduli dengan perasaan orang lain',
                    'Lebih suka bekerja dalam tim',
                    'Komunikator yang baik'
                ]
            ],
            'E' => [
                'title' => 'Excellence (Keunggulan)',
                'description' => 'Anda adalah orang yang selalu berusaha mencapai hasil terbaik dalam segala hal. Anda memiliki standar tinggi, kompetitif, dan terus berusaha mengembangkan diri.',
                'characteristics' => [
                    'Berorientasi pada hasil dan pencapaian',
                    'Memiliki standar kualitas yang tinggi',
                    'Selalu mencari cara untuk improvement',
                    'Kompetitif dan ambisius',
                    'Fokus pada detail dan perfeksi'
                ]
            ],
            'I' => [
                'title' => 'Integrity (Integritas)',
                'description' => 'Anda adalah orang yang menjunjung tinggi nilai-nilai moral dan etika. Anda konsisten antara perkataan dan perbuatan, jujur, dan dapat dipercaya.',
                'characteristics' => [
                    'Konsisten dengan nilai dan prinsip',
                    'Jujur dan dapat dipercaya',
                    'Berani mengambil tanggung jawab',
                    'Tidak mudah terpengaruh tekanan',
                    'Menjadi teladan bagi orang lain'
                ]
            ]
        ];

        return $descriptions[$personality] ?? $descriptions['H'];
    }

    /**
     * Get answer distribution (1-5 scale)
     */
    private function getAnswerDistribution($answers)
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $answers->where('answer_value', $i)->count();
            $percentage = $answers->count() > 0 ? round(($count / $answers->count()) * 100, 1) : 0;
            
            $distribution[$i] = [
                'count' => $count,
                'percentage' => $percentage,
                'label' => $this->getAnswerLabel($i)
            ];
        }

        return $distribution;
    }

    /**
     * Get answer label
     */
    private function getAnswerLabel($value)
    {
        $labels = [
            1 => 'Sangat Tidak Setuju',
            2 => 'Tidak Setuju',
            3 => 'Netral',
            4 => 'Setuju',
            5 => 'Sangat Setuju'
        ];

        return $labels[$value] ?? 'Unknown';
    }
}