@extends('layouts.app')
@include('components.navbar')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mbti.css') }}">
@endsection

@section('content')
<div class="container test-page">
    <!-- Header -->
    <div class="section">
        <h1 class="text-h1">HEI Personality Assessment</h1>
        <p class="text-body">Jawab pertanyaan-pertanyaan berikut untuk mengetahui tipe kepribadian HEI Anda</p>
    </div>

    <!-- Progress -->
    <div class="progress-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <span class="text-h4">Assessment Progress</span>
            <span class="text-h3" style="color: #6366f1;" id="progress-text">0%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill" style="width: 0%;"></div>
        </div>
        <p class="text-body" style="margin-top: 1rem; color: var(--text-muted);" id="progress-detail">0 of {{ count($questions) }} questions completed</p>
    </div>

    <div class="success-message" id="success-message">
        Jawaban berhasil disimpan!
    </div>

    <!-- Questions -->
    @foreach($questions as $index => $question)
    <div class="question-card" data-question-id="{{ $question->id }}">
        <p class="text-body" style="color: var(--text-muted);">Question {{ $index + 1 }} of {{ count($questions) }}</p>
        <h3 class="text-h3" style="margin: 1.5rem 0;">{{ $question->question_text }}</h3>
        
        <div class="answer-options">
            <button class="answer-option text-body" data-value="1">Strongly Disagree</button>
            <button class="answer-option text-body" data-value="2">Disagree</button>
            <button class="answer-option text-body" data-value="3">Neutral</button>
            <button class="answer-option text-body" data-value="4">Agree</button>
            <button class="answer-option text-body" data-value="5">Strongly Agree</button>
        </div>
    </div>
    @endforeach

    <button class="btn-submit text-body" id="submit-btn" disabled>
        Submit All Answers
    </button>
</div>
@endsection

@section('scripts')
    @if (session('set_hei_logged_in'))
    <script>
        sessionStorage.setItem("hei_logged_in", true);
    </script>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const answers = {};
        const totalQuestions = parseInt('{{ count($questions) }}');
        let answeredQuestions = 0;

        document.querySelectorAll('.answer-option').forEach(button => {
            button.addEventListener('click', function() {
                const questionCard = this.closest('.question-card');
                const questionId = questionCard.dataset.questionId;
                const value = this.dataset.value;

                // Hapus 'selected' dari semua jawaban di satu soal
                questionCard.querySelectorAll('.answer-option').forEach(opt => {
                    opt.classList.remove('selected');
                });

                // Tambah class 'selected' ke tombol yang diklik
                this.classList.add('selected');

                if (!answers[questionId]) {
                    answeredQuestions++;
                }
                answers[questionId] = value;

                updateProgress();

                if (answeredQuestions === totalQuestions) {
                    document.getElementById('submit-btn').disabled = false;
                }
            });
        });

        function updateProgress() {
            const percentage = Math.round((answeredQuestions / totalQuestions) * 100);
            document.getElementById('progress-text').textContent = percentage + '%';
            document.getElementById('progress-fill').style.width = percentage + '%';
            document.getElementById('progress-detail').textContent = `${answeredQuestions} of ${totalQuestions} questions completed`;
        }

        document.getElementById('submit-btn').addEventListener('click', function() {
            this.disabled = true;
            this.textContent = 'Submitting...';

            document.getElementById('success-message').style.display = 'block';

            setTimeout(() => {
                this.textContent = 'Answers Submitted Successfully!';
                this.style.background = 'var(--green-soft)';
            }, 1000);
        });
    });
</script>

@endsection
