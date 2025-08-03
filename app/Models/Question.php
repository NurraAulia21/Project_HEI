<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'category',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship dengan Answer
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    // Scope untuk pertanyaan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk pertanyaan berdasarkan urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Scope untuk pertanyaan berdasarkan kategori
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Method untuk mendapatkan nama kategori
    public function getCategoryNameAttribute()
    {
        $categories = [
            'H' => 'Harmony',
            'E' => 'Excellence',
            'I' => 'Integrity'
        ];

        return $categories[$this->category] ?? 'Unknown';
    }

    // Method untuk cek apakah pertanyaan sudah dijawab
    public function hasAnswers()
    {
        return $this->answers()->exists();
    }

    // Method untuk mendapat jumlah jawaban
    public function getAnswerCount()
    {
        return $this->answers()->count();
    }

    // Method untuk mendapat rata-rata jawaban
    public function getAverageAnswer()
    {
        return round($this->answers()->avg('answer_value'), 2);
    }
}