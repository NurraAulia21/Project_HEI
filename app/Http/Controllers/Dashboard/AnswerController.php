<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\User;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    /**
     * Display a listing of answers grouped by users
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $category = $request->get('category');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Base query for users who have answered questions
        $query = User::whereHas('answers');

        // Apply date filters
        if ($dateFrom) {
            $query->whereHas('answers', function ($q) use ($dateFrom) {
                $q->whereDate('created_at', '>=', $dateFrom);
            });
        }

        if ($dateTo) {
            $query->whereHas('answers', function ($q) use ($dateTo) {
                $q->whereDate('created_at', '<=', $dateTo);
            });
        }

        // Get users with their answer statistics
        $users = $query->with(['answers' => function ($q) use ($category) {
                if ($category) {
                    $q->whereHas('question', function ($qq) use ($category) {
                        $qq->where('category', $category);
                    });
                }
            }])
            ->get()
            ->map(function ($user) use ($category) {
                // Calculate statistics for each user
                $answersQuery = $user->answers();
                
                if ($category) {
                    $answersQuery->whereHas('question', function ($q) use ($category) {
                        $q->where('category', $category);
                    });
                }

                $answers = $answersQuery->with('question')->get();
                
                // Calculate category averages
                $categoryAverages = [
                    'H' => $this->calculateCategoryAverage($user->id, 'H'),
                    'E' => $this->calculateCategoryAverage($user->id, 'E'),
                    'I' => $this->calculateCategoryAverage($user->id, 'I'),
                ];

                // Determine dominant personality (highest average)
                $dominantPersonality = collect($categoryAverages)->sortDesc()->keys()->first();

                return [
                    'user' => $user,
                    'total_answers' => $answers->count(),
                    'average_score' => $answers->avg('answer_value'),
                    'last_answered' => $answers->max('created_at'),
                    'category_averages' => $categoryAverages,
                    'dominant_personality' => $dominantPersonality,
                    'completion_status' => $this->getCompletionStatus($user->id),
                ];
            })
            ->sortByDesc('last_answered');

        // Statistics
        $stats = [
            'total_responses' => Answer::count(),
            'total_users' => User::whereHas('answers')->count(),
            'total_questions' => Question::where('is_active', true)->count(),
            'avg_completion' => $this->getAverageCompletion(),
        ];

        return view('admin.answers.index', compact('users', 'stats', 'category', 'dateFrom', 'dateTo'));
    }

    /**
     * Show detailed answers for a specific user
     */
    public function show(User $user, Request $request)
    {
        $attempt = $request->get('attempt', 1);

        // Get user's answers for specific attempt
        $answers = Answer::where('user_id', $user->id)
            ->where('attempt', $attempt)
            ->with(['question'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Get all attempts for this user
        $attempts = Answer::where('user_id', $user->id)
            ->select('attempt')
            ->distinct()
            ->orderBy('attempt', 'asc')
            ->pluck('attempt');

        // Calculate detailed statistics
        $stats = [
            'total_answers' => $answers->count(),
            'completion_percentage' => $this->getCompletionPercentage($user->id, $attempt),
            'category_stats' => $this->getCategoryStats($user->id, $attempt),
            'answer_distribution' => $this->getAnswerDistribution($user->id, $attempt),
        ];

        return view('admin.answers.show', compact('user', 'answers', 'attempts', 'attempt', 'stats'));
    }

    /**
     * Remove specific answer
     */
    public function destroy(Answer $answer)
    {
        $userName = $answer->user->name;
        $questionText = $answer->question->question_text;
        
        $answer->delete();

        return redirect()->back()->with('success', "Jawaban {$userName} untuk pertanyaan '{$questionText}' berhasil dihapus!");
    }

    /**
     * Remove all answers for a specific user
     */
    public function destroyUserAnswers(User $user, Request $request)
    {
        $attempt = $request->get('attempt');

        $query = Answer::where('user_id', $user->id);

        if ($attempt) {
            $query->where('attempt', $attempt);
            $deletedCount = $query->count();
            $query->delete();
            
            return redirect()->route('admin.answers.index')
                ->with('success', "Semua jawaban {$user->name} (percobaan ke-{$attempt}) berhasil dihapus! Total: {$deletedCount} jawaban");
        } else {
            $deletedCount = $query->count();
            $query->delete();
            
            return redirect()->route('admin.answers.index')
                ->with('success', "Semua jawaban {$user->name} berhasil dihapus! Total: {$deletedCount} jawaban");
        }
    }

    /**
     * Calculate average score for a category
     */
    private function calculateCategoryAverage($userId, $category)
    {
        return Answer::where('user_id', $userId)
            ->whereHas('question', function ($q) use ($category) {
                $q->where('category', $category);
            })
            ->avg('answer_value') ?? 0;
    }

    /**
     * Get completion status for a user
     */
    private function getCompletionStatus($userId)
    {
        $totalQuestions = Question::where('is_active', true)->count();
        $answeredQuestions = Answer::where('user_id', $userId)->distinct('question_id')->count();
        
        if ($totalQuestions == 0) return 'No Questions';
        
        $percentage = ($answeredQuestions / $totalQuestions) * 100;
        
        if ($percentage >= 100) return 'Complete';
        if ($percentage >= 75) return 'Almost Complete';
        if ($percentage >= 50) return 'In Progress';
        return 'Just Started';
    }

    /**
     * Get average completion percentage across all users
     */
    private function getAverageCompletion()
    {
        $totalQuestions = Question::where('is_active', true)->count();
        
        if ($totalQuestions == 0) return 0;

        $users = User::whereHas('answers')->get();
        
        if ($users->isEmpty()) return 0;

        $totalCompletion = $users->sum(function ($user) use ($totalQuestions) {
            $answered = Answer::where('user_id', $user->id)->distinct('question_id')->count();
            return ($answered / $totalQuestions) * 100;
        });

        return round($totalCompletion / $users->count(), 1);
    }

    /**
     * Get completion percentage for specific user and attempt
     */
    private function getCompletionPercentage($userId, $attempt)
    {
        $totalQuestions = Question::where('is_active', true)->count();
        $answeredQuestions = Answer::where('user_id', $userId)
            ->where('attempt', $attempt)
            ->distinct('question_id')
            ->count();
        
        return $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100, 1) : 0;
    }

    /**
     * Get category statistics for user
     */
    private function getCategoryStats($userId, $attempt)
    {
        return [
            'H' => [
                'average' => Answer::where('user_id', $userId)
                    ->where('attempt', $attempt)
                    ->whereHas('question', fn($q) => $q->where('category', 'H'))
                    ->avg('answer_value') ?? 0,
                'count' => Answer::where('user_id', $userId)
                    ->where('attempt', $attempt)
                    ->whereHas('question', fn($q) => $q->where('category', 'H'))
                    ->count(),
            ],
            'E' => [
                'average' => Answer::where('user_id', $userId)
                    ->where('attempt', $attempt)
                    ->whereHas('question', fn($q) => $q->where('category', 'E'))
                    ->avg('answer_value') ?? 0,
                'count' => Answer::where('user_id', $userId)
                    ->where('attempt', $attempt)
                    ->whereHas('question', fn($q) => $q->where('category', 'E'))
                    ->count(),
            ],
            'I' => [
                'average' => Answer::where('user_id', $userId)
                    ->where('attempt', $attempt)
                    ->whereHas('question', fn($q) => $q->where('category', 'I'))
                    ->avg('answer_value') ?? 0,
                'count' => Answer::where('user_id', $userId)
                    ->where('attempt', $attempt)
                    ->whereHas('question', fn($q) => $q->where('category', 'I'))
                    ->count(),
            ],
        ];
    }

    /**
     * Get answer distribution (1-5 scale)
     */
    private function getAnswerDistribution($userId, $attempt)
    {
        return Answer::where('user_id', $userId)
            ->where('attempt', $attempt)
            ->select('answer_value', DB::raw('count(*) as count'))
            ->groupBy('answer_value')
            ->orderBy('answer_value')
            ->pluck('count', 'answer_value')
            ->toArray();
    }
}