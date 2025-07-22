<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class TestController extends Controller
{
    public function index()
    {
        // Ambil 5 pertanyaan pertama yang aktif
        $questions = Question::active()
                           ->ordered()
                           ->limit(5)
                           ->get();

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
}