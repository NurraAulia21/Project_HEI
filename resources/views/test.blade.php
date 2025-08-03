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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded, starting test functionality');

        const answers = {};
        const totalQuestions = parseInt('{{ count($questions) }}');
        let answeredQuestions = 0;

        // CSRF Token
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (!csrfTokenElement) {
            alert('CSRF token not found! Please refresh the page.');
            return;
        }
        const csrfToken = csrfTokenElement.getAttribute('content');

        const answerOptions = document.querySelectorAll('.answer-option');
        console.log('Found answer options:', answerOptions.length);

        answerOptions.forEach((button, index) => {
            button.addEventListener('click', function() {
                console.log('Button clicked!', this);
                
                const questionCard = this.closest('.question-card');
                const questionId = questionCard.dataset.questionId;
                const value = this.dataset.value;
                
                console.log('Question ID:', questionId, 'Value:', value);

                // Visual feedback
                questionCard.querySelectorAll('.answer-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');

                if (!answers[questionId]) {
                    answeredQuestions++;
                }
                answers[questionId] = value;

                // Submit answer dengan detailed logging
                submitSingleAnswer(questionId, value);

                updateProgress();

                if (answeredQuestions === totalQuestions) {
                    document.getElementById('submit-btn').disabled = false;
                    console.log('All questions answered, submit button enabled');
                }
            });
        });

        function submitSingleAnswer(questionId, answerValue) {
            console.log('=== SUBMITTING ANSWER ===');
            console.log('Question ID:', questionId);
            console.log('Answer Value:', answerValue);
            console.log('URL:', '{{ route("test.submit-answer") }}');
            
            fetch('{{ route("test.submit-answer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    question_id: questionId,
                    answer: answerValue
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('=== RESPONSE DATA ===');
                console.log('Full response:', data);
                console.log('Status:', data.status);
                console.log('User ID:', data.user_id);
                console.log('Attempt:', data.attempt);
                console.log('Answer ID:', data.answer_id);
                
                if (data.status === 'success') {
                    showSuccessMessage();
                } else {
                    console.error('Error saving answer:', data);
                    alert('Error saving answer: ' + JSON.stringify(data));
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                alert('Network error: ' + error.message);
            });
        }

        function showSuccessMessage() {
            const successMsg = document.getElementById('success-message');
            if (successMsg) {
                successMsg.style.display = 'block';
                setTimeout(() => {
                    successMsg.style.display = 'none';
                }, 2000);
            }
        }

        function updateProgress() {
            const percentage = Math.round((answeredQuestions / totalQuestions) * 100);
            console.log('Updating progress:', percentage + '%');
            
            const progressText = document.getElementById('progress-text');
            const progressFill = document.getElementById('progress-fill');
            const progressDetail = document.getElementById('progress-detail');
            
            if (progressText) progressText.textContent = percentage + '%';
            if (progressFill) progressFill.style.width = percentage + '%';
            if (progressDetail) progressDetail.textContent = `${answeredQuestions} of ${totalQuestions} questions completed`;
        }

        // Submit test with detailed logging
        const submitBtn = document.getElementById('submit-btn');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                console.log('=== SUBMITTING TEST ===');
                console.log('Answers collected:', answers);
                console.log('Total answered:', answeredQuestions);
                console.log('Submit URL:', '{{ route("test.submit") }}');
                
                this.disabled = true;
                this.textContent = 'Processing...';

                fetch('{{ route("test.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        answers: answers
                    })
                })
                .then(response => {
                    console.log('Submit response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('=== SUBMIT RESPONSE ===');
                    console.log('Full response:', data);
                    
                    if (data.status === 'success') {
                        this.textContent = 'Redirecting to Results...';
                        this.style.background = 'var(--green-soft)';
                        
                        console.log('Redirecting to:', data.redirect_url);
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1000);
                    } else {
                        console.error('Submit error:', data);
                        this.textContent = 'Error - Please try again';
                        this.disabled = false;
                        this.style.background = 'var(--red-soft)';
                        alert('Submit error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Submit network error:', error);
                    this.textContent = 'Network Error - Please try again';
                    this.disabled = false;
                    this.style.background = 'var(--red-soft)';
                    alert('Network error: ' + error.message);
                });
            });
        }
    });
</script>
@endsection