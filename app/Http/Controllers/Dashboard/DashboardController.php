<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Admin;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with overview statistics
     */
    public function adminIndex()
    {
        // Statistik untuk dashboard utama
        $stats = [
            'total_questions' => Question::count(),
            'active_questions' => Question::where('is_active', true)->count(),
            'total_admins' => Admin::count(),
            'active_admins' => Admin::where('is_active', true)->count(),
            'total_users' => User::count(),
            'total_answers' => Answer::count(),
            'users_completed' => Answer::select('user_id')->distinct()->count(),
        ];

        // Statistik per kategori
        $categoryStats = [
            'harmony' => Question::where('category', 'H')->count(),
            'excellence' => Question::where('category', 'E')->count(),
            'integrity' => Question::where('category', 'I')->count(),
        ];

        // Recent activities (5 jawaban terbaru)
        $recentAnswers = Answer::with(['user', 'question'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Data untuk chart (jawaban per hari dalam 7 hari terakhir)
        $chartData = Answer::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', compact('stats', 'categoryStats', 'recentAnswers', 'chartData'));
    }

    /**
     * Display dashboard with questions list (method lama, masih dipertahankan)
     */
    public function index()
    {
        $questions = Question::orderBy('order', 'asc')->get();
        return view('dashboard.index', compact('questions'));
    }

    /**
     * Store a new question (manual input)
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category' => 'required|in:H,E,I',
            'order' => 'required|integer|min:1|unique:questions,order',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;

        Question::create($data);

        return redirect()->route('dashboard.index')->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing a question
     */
    public function edit(Question $question)
    {
        return view('dashboard.edit', compact('question'));
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'category' => 'required|in:H,E,I',
            'order' => 'required|integer|min:1|unique:questions,order,' . $question->id,
            'is_active' => 'sometimes|boolean'
        ]);

        $data = [
            'question_text' => $request->question_text,
            'category' => $request->category,
            'order' => $request->order,
        ];

        if ($request->has('is_active') && $request->route()->getName() === 'dashboard.store') {
            $data['is_active'] = $request->has('is_active') ? true : false;
        } elseif ($request->has('is_active') && $request->route()->getName() === 'dashboard.update') {
            $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        $question->update($data);

        return redirect()->route('dashboard.index')->with('success', 'Pertanyaan berhasil diupdate!');
    }

    /**
     * Toggle question status (AJAX)
     */
    public function toggleStatus(Question $question)
    {
        $question->update(['is_active' => !$question->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $question->is_active,
            'message' => 'Status pertanyaan berhasil diubah!'
        ]);
    }

    /**
     * Remove the specified question
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('dashboard.index')->with('success', 'Pertanyaan berhasil dihapus!');
    }

    /**
     * Show import CSV form
     */
    public function importForm()
    {
        return view('dashboard.import');
    }

    /**
     * Preview CSV data before importing
     */
    public function previewCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $file = $request->file('csv_file');
            $csvData = file($file->getRealPath());
            
            $header = trim(array_shift($csvData));
            
            $previewData = [];
            $errors = [];
            $line = 2; 
            
            foreach ($csvData as $row) {
                $row = trim($row);
                
                if (empty($row)) {
                    continue;
                }
                
                $data = str_getcsv($row, ';');
                
                if (count($data) >= 4) {
                    $questionText = trim($data[0]);
                    $category = strtoupper(trim($data[1]));
                    $order = (int) trim($data[2]);
                    $isActive = filter_var(trim($data[3]), FILTER_VALIDATE_BOOLEAN);
                    
                    $validator = Validator::make([
                        'question_text' => $questionText,
                        'category' => $category,
                        'order' => $order,
                    ], [
                        'question_text' => 'required|string',
                        'category' => 'required|in:H,E,I',
                        'order' => 'required|integer|min:1',
                    ]);
                    
                    if ($validator->fails()) {
                        $errors[] = "Baris {$line}: " . implode(', ', $validator->errors()->all());
                    } else {
                        if (Question::where('order', $order)->exists()) {
                            $errors[] = "Baris {$line}: Urutan {$order} sudah digunakan";
                        }
                        
                        $previewData[] = [
                            'question_text' => $questionText,
                            'category' => $category,
                            'order' => $order,
                            'is_active' => $isActive,
                            'line' => $line
                        ];
                    }
                } else {
                    $errors[] = "Baris {$line}: Format data tidak lengkap";
                }
                
                $line++;
            }
            
            session([
                'csv_preview_data' => $previewData,
                'csv_errors' => $errors
            ]);
            
            return view('dashboard.preview', compact('previewData', 'errors'));
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard.import.form')->with('error', 'Gagal memproses file CSV: ' . $e->getMessage());
        }
    }

    /**
     * Import CSV data to database
     */
    public function importCsv(Request $request)
    {
        $previewData = session('csv_preview_data');
        $errors = session('csv_errors', []);
        
        if (empty($previewData)) {
            return redirect()->route('dashboard.import.form')->with('error', 'Data preview tidak ditemukan. Silakan upload file CSV terlebih dahulu.');
        }
        
        if (!empty($errors)) {
            return redirect()->route('dashboard.import.form')->with('error', 'Terdapat error pada data CSV. Silakan perbaiki terlebih dahulu.');
        }
        
        try {
            $importedCount = 0;
            
            foreach ($previewData as $data) {
                Question::create([
                    'question_text' => $data['question_text'],
                    'category' => $data['category'],
                    'order' => $data['order'],
                    'is_active' => $data['is_active']
                ]);
                $importedCount++;
            }
            
            session()->forget(['csv_preview_data', 'csv_errors']);
            
            return redirect()->route('dashboard.index')->with('success', "Berhasil mengimpor {$importedCount} pertanyaan dari CSV!");
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard.import.form')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Clear all questions
     */
    public function clearQuestions()
    {
        Question::truncate();
        return redirect()->route('dashboard.index')->with('success', 'Semua pertanyaan berhasil dihapus!');
    }
}