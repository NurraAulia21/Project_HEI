<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index()
    {
        // Initialize session for current user
        $this->initializeTemporarySession();
        
        // Get active questions
        $questions = Question::where('is_active', true)->orderBy('order')->get();

        return view('test', compact('questions'));
    }

    /**
     * Initialize temporary session for current user
     * TODO: Replace with auth()->id() when proper session implementation is ready
     */
    private function initializeTemporarySession()
    {
        if (!session()->has('current_test_user_id')) {
            if (Auth::check()) {
                $userId = Auth::id();
                session(['current_test_user_id' => $userId]);
                
                $currentAttempt = $this->determineCurrentAttempt($userId);
                session(['current_attempt' => $currentAttempt]);
            } else {
                abort(401, 'Please login first');
            }
        } else {
            if (!session()->has('current_attempt')) {
                $userId = session('current_test_user_id');
                $currentAttempt = $this->determineCurrentAttempt($userId);
                session(['current_attempt' => $currentAttempt]);
            }
        }
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|in:1,2,3,4,5'
        ]);
        
        $this->initializeTemporarySession();
        $userId = (int) session('current_test_user_id');
        $attempt = (int) session('current_attempt');
        $questionId = (int) $request->question_id;
        $answerValue = (int) $request->answer;
        
        try {
            $answer = \DB::transaction(function () use ($userId, $questionId, $attempt, $answerValue) {
                // Check for existing answer with row lock
                $existing = \DB::table('answers')
                              ->where('user_id', $userId)
                              ->where('question_id', $questionId)
                              ->where('attempt', $attempt)
                              ->lockForUpdate()
                              ->first();
                
                if ($existing) {
                    // Update existing answer
                    \DB::table('answers')
                      ->where('id', $existing->id)
                      ->update([
                          'answer_value' => $answerValue,
                          'answered_at' => now(),
                          'updated_at' => now()
                      ]);
                    
                    return Answer::find($existing->id);
                } else {
                    // Create new answer
                    $answerId = \DB::table('answers')->insertGetId([
                        'user_id' => $userId,
                        'question_id' => $questionId,
                        'attempt' => $attempt,
                        'answer_value' => $answerValue,
                        'answered_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    return Answer::find($answerId);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Answer submitted successfully',
                'user_id' => $userId,
                'attempt' => $attempt,
                'answer_id' => $answer->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save answer'
            ], 500);
        }
    }

    public function submitTest(Request $request)
    {
        $this->initializeTemporarySession();
        $userId = session('current_test_user_id');
        $attempt = (int) session('current_attempt');
        
        // Validate all questions are answered
        $totalQuestions = Question::where('is_active', true)->count();
        $answeredQuestions = Answer::where('user_id', (int) $userId)
                                  ->where('attempt', (int) $attempt)
                                  ->count();
        
        if ($answeredQuestions < $totalQuestions) {
            return response()->json([
                'status' => 'error',
                'message' => "Please answer all questions before submitting. Answered: {$answeredQuestions}/{$totalQuestions}"
            ], 422);
        }
        
        // Clear session after successful submission
        session()->forget(['current_test_user_id', 'current_attempt']);
        
        return response()->json([
            'status' => 'success',
            'redirect_url' => route('test.result', ['user' => $userId, 'attempt' => $attempt])
        ]);
    }

    /**
     * Determine current attempt number for user
     */
    private function determineCurrentAttempt($userId)
    {
        // Handle forced new attempt (from retake)
        if (session('force_new_attempt')) {
            session()->forget('force_new_attempt');
            
            $latestAttempt = Answer::where('user_id', $userId)->max('attempt');
            return $latestAttempt ? (int) $latestAttempt + 1 : 1;
        }
        
        // Check latest attempt
        $latestAttempt = Answer::where('user_id', $userId)->max('attempt');
        
        if ($latestAttempt) {
            // Check if latest attempt is complete
            $totalQuestions = Question::where('is_active', true)->count();
            $answeredInLatestAttempt = Answer::where('user_id', $userId)
                                           ->where('attempt', (int) $latestAttempt)
                                           ->count();
            
            // If latest attempt is complete, create new attempt
            if ($answeredInLatestAttempt >= $totalQuestions) {
                return (int) $latestAttempt + 1;
            }
            
            // Continue with current attempt
            return (int) $latestAttempt;
        }
        
        // First attempt
        return 1;
    }

    public function showResult($userId, $attempt = 1)
    {
        // Basic authorization check
        if (session('current_test_user_id') != $userId && !Auth::check()) {
            abort(403, 'Unauthorized access');
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('test')->with('error', 'User tidak ditemukan');
        }

        $answers = Answer::where('user_id', $userId)
                        ->where('attempt', $attempt)
                        ->with(['question'])
                        ->orderBy('created_at', 'asc')
                        ->get();

        if ($answers->isEmpty()) {
            return redirect()->route('test')->with('error', 'Tidak ada data hasil test untuk user ini');
        }

        $categoryResults = $this->calculateCategoryResults($answers);
        $dominantPersonality = $this->getDominantPersonality($categoryResults);
        
        $availableAttempts = Answer::where('user_id', $userId)
                                  ->select('attempt')
                                  ->distinct()
                                  ->orderBy('attempt')
                                  ->pluck('attempt');

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

    public function retakeTest()
    {
        if (!Auth::check()) {
            return redirect()->route('hei-personality-test')->with('error', 'Please login first');
        }

        session()->forget('current_test_user_id');
        session(['force_new_attempt' => true]);
        
        return redirect()->route('test')->with('info', 'Memulai test baru...');
    }

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

    private function getDominantPersonality($categoryResults)
    {
        $highest = collect($categoryResults)->sortByDesc('average');
        
        if ($highest->isEmpty()) {
            return 'H';
        }

        return $highest->keys()->first();
    }

    private function getCategoryName($category)
    {
        $names = [
            'H' => 'Harmony',
            'E' => 'Excellence', 
            'I' => 'Integrity'
        ];

        return $names[$category] ?? 'Unknown';
    }

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