<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions
     */
    public function index()
    {
        $questions = Question::orderBy('order', 'asc')->get();
        
        $stats = [
            'total_questions' => $questions->count(),
            'active_questions' => $questions->where('is_active', true)->count(),
            'harmony_count' => $questions->where('category', 'H')->count(),
            'excellence_count' => $questions->where('category', 'E')->count(),
            'integrity_count' => $questions->where('category', 'I')->count(),
        ];

        return view('admin.questions.index', compact('questions', 'stats'));
    }

    /**
     * Show the form for creating a new question
     */
    public function create()
    {
        // Get next available order number
        $nextOrder = Question::max('order') + 1;
        
        return view('admin.questions.create', compact('nextOrder'));
    }

    /**
     * Store a newly created question
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'category' => 'required|in:H,E,I',
            'order' => 'required|integer|min:1|unique:questions,order',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;

        Question::create($data);

        return redirect()->route('admin.questions.index')->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Display the specified question
     */
    public function show(Question $question)
    {
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified question
     */
    public function edit(Question $question)
    {
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'category' => 'required|in:H,E,I',
            'order' => 'required|integer|min:1|unique:questions,order,' . $question->id,
            'is_active' => 'boolean'
        ]);

        $data = [
            'question_text' => $request->question_text,
            'category' => $request->category,
            'order' => $request->order,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        $question->update($data);

        return redirect()->route('admin.questions.index')->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified question
     */
    public function destroy(Question $question)
    {
        // Check if question has answers
        $hasAnswers = $question->answers()->exists();
        
        if ($hasAnswers) {
            return redirect()->route('admin.questions.index')->with('error', 'Tidak dapat menghapus pertanyaan yang sudah dijawab mahasiswa!');
        }

        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Pertanyaan berhasil dihapus!');
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
     * Show import CSV form
     */
    public function importForm()
    {
        return view('admin.questions.import');
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
                        'question_text' => 'required|string|max:1000',
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
                    $errors[] = "Baris {$line}: Format data tidak lengkap (butuh 4 kolom: pertanyaan, kategori, urutan, status)";
                }
                
                $line++;
            }
            
            session([
                'csv_preview_data' => $previewData,
                'csv_errors' => $errors
            ]);
            
            return view('admin.questions.preview', compact('previewData', 'errors'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.import.form')->with('error', 'Gagal memproses file CSV: ' . $e->getMessage());
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
            return redirect()->route('admin.questions.import.form')->with('error', 'Data preview tidak ditemukan. Silakan upload file CSV terlebih dahulu.');
        }
        
        if (!empty($errors)) {
            return redirect()->route('admin.questions.import.form')->with('error', 'Terdapat error pada data CSV. Silakan perbaiki terlebih dahulu.');
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
            
            return redirect()->route('admin.questions.index')->with('success', "Berhasil mengimpor {$importedCount} pertanyaan dari CSV!");
            
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.import.form')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Clear all questions
     */
    public function clearQuestions()
    {
        // Check if any questions have answers
        $questionsWithAnswers = Question::whereHas('answers')->count();
        
        if ($questionsWithAnswers > 0) {
            return redirect()->route('admin.questions.index')->with('error', "Tidak dapat menghapus semua pertanyaan karena {$questionsWithAnswers} pertanyaan sudah dijawab mahasiswa!");
        }

        $deletedCount = Question::count();
        Question::truncate();
        
        return redirect()->route('admin.questions.index')->with('success', "Berhasil menghapus {$deletedCount} pertanyaan!");
    }

    /**
     * Bulk toggle status for selected questions
     */
    public function bulkToggle(Request $request)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
            'status' => 'required|boolean'
        ]);

        $updatedCount = Question::whereIn('id', $request->question_ids)
                               ->update(['is_active' => $request->status]);

        $statusText = $request->status ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} pertanyaan berhasil {$statusText}!"
        ]);
    }

    /**
     * Reorder questions
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order' => 'required|integer|min:1'
        ]);

        try {
            foreach ($request->questions as $questionData) {
                Question::where('id', $questionData['id'])
                       ->update(['order' => $questionData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Urutan pertanyaan berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()
            ], 500);
        }
    }
}