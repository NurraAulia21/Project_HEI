<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('answer_value'); // 1-5 (Strongly Disagree to Strongly Agree)
            $table->integer('attempt')->default(1); // Percobaan ke-1, ke-2, dst
            $table->timestamp('answered_at');
            $table->timestamps();
            
            // Pastikan kombinasi user_id, question_id, dan attempt harus unik
            $table->unique(['user_id', 'question_id', 'attempt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};