<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id', 
        'answer_value',
        'answered_at'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan Question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Scope untuk mendapatkan jawaban berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk mendapatkan jawaban berdasarkan kategori pertanyaan
    public function scopeByCategory($query, $category)
    {
        return $query->whereHas('question', function ($q) use ($category) {
            $q->where('category', $category);
        });
    }

    // Method untuk mendapatkan label jawaban
    public function getAnswerLabelAttribute()
    {
        $labels = [
            1 => 'Strongly Disagree',
            2 => 'Disagree', 
            3 => 'Neutral',
            4 => 'Agree',
            5 => 'Strongly Agree'
        ];

        return $labels[$this->answer_value] ?? 'Unknown';
    }

    // Method untuk menghitung rata-rata jawaban user berdasarkan kategori
    public static function getAverageByUserAndCategory($userId, $category)
    {
        return self::byUser($userId)
                   ->byCategory($category)
                   ->avg('answer_value');
    }
}