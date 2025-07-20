<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Personality Test</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="dashboard-bg">
    <!-- Navbar -->
    <nav class="navbar">
        <button id="personality-types-btn" class="navbar-btn">
            Personality Types
        </button>
    </nav>
    <!-- Main Content -->
    <main class="main-content">
        <div class="main-card">
            <h1 class="main-title">It's so incredible to finally be understood.</h1>
            <p class="main-desc">
                Only 10 minutes to get a <span class="highlight">"freakishly accurate"</span> description of who you are and why you do things the way you do.
            </p>
            <button id="take-test-btn" class="main-action-btn">
                Take the Test
            </button>
        </div>
    </main>
    <!-- Login Overlay -->
    <div id="login-overlay" class="overlay-bg hidden">
        <div class="overlay-card">
            <button id="close-login" class="overlay-close">&times;</button>
            <h2 class="overlay-title" style="margin-top: 0px; margin-bottom: 10px;">Login</h2>
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="form-btn" style="padding: 0.40rem; font-size: medium;">Login</button>
            </form>
            <div class="overlay-footer">
                <span>Don't have an account?</span>
                <button id="show-register" class="link-btn">Sign Up</button>
            </div>
            <p class="or-divider" style="font-size: small;">Or Login via Google</p>
            <a href="{{ route('google.login') }}" class="form-btn google-btn" style="padding: 0px;width: 100%;display: flex;align-items: center;justify-content: center;">
                <img src="img/google.png" alt="Google Logo" style="width:40px;height:40px;">
                <span style="text-decoration:none;color:#fff;font-weight:400">Login with Google</span>
            </a>
        </div>
    </div>
    <!-- Register Overlay -->
    <div id="register-overlay" class="overlay-bg hidden">
        <div class="overlay-card">
            <button id="close-register" class="overlay-close">&times;</button>
            <h2 class="overlay-title" style="margin-top: 0px; margin-bottom: 10px;">Sign Up</h2>
            <form id="register-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" required>
                </div>
                <button type="submit" class="form-btn" class="form-btn" style="padding: 0.40rem; font-size: medium;">Sign Up</button>
                <p class="or-divider" style="font-size: small;">Already have an account?</p>
                <a href="{{ route('google.login') }}" class="form-btn google-btn" style="padding: 0px;width: 100%;display: flex;align-items: center;justify-content: center;">
                    <img src="img/google.png" alt="Google Logo" style="width:40px;height:40px;">
                    <span style="text-decoration:none;color:#fff;font-weight:400">Login with Google</span>
                </a>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('take-test-btn').onclick = function() {
            document.getElementById('login-overlay').classList.remove('hidden');
        };
        document.getElementById('close-login').onclick = function() {
            document.getElementById('login-overlay').classList.add('hidden');
        };
        document.getElementById('show-register').onclick = function() {
            document.getElementById('login-overlay').classList.add('hidden');
            document.getElementById('register-overlay').classList.remove('hidden');
        };
        document.getElementById('close-register').onclick = function() {
            document.getElementById('register-overlay').classList.add('hidden');
            document.getElementById('login-overlay').classList.remove('hidden');
        };
        // document.getElementById('register-form').onsubmit = function(e) {
        //     e.preventDefault();
        //     document.getElementById('register-overlay').classList.add('hidden');
        //     document.getElementById('login-overlay').classList.remove('hidden');
        // };

    <title>HEI Assessment Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #e6e7ee;
            min-height: 100vh;
            padding: 2rem;
        }

        :root {
            --bg-primary: #e6e7ee;
            --bg-card: #e6e7ee;
            
            --purple-soft: #b19cd9;
            --green-soft: #a8d5ba;
            --blue-soft: #a8c8ec;
            --yellow-soft: #f7e98e;
            
            --shadow-dark: #d1d2d9;
            --shadow-light: #fbfcff;
            
            --text-primary: #3e4152;
            --text-secondary: #6c7293;
            --text-muted: #a7a9b8;
        }

        .text-h1 { font-size: 2.5rem; font-weight: 700; line-height: 1.2; color: var(--text-primary); }
        .text-h2 { font-size: 2rem; font-weight: 600; line-height: 1.3; color: var(--text-primary); }
        .text-h3 { font-size: 1.5rem; font-weight: 500; line-height: 1.4; color: var(--text-primary); }
        .text-h4 { font-size: 1.25rem; font-weight: 500; line-height: 1.5; color: var(--text-primary); }
        .text-body { font-size: 1rem; font-weight: 400; line-height: 1.6; color: var(--text-secondary); }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .section {
            background: var(--bg-card);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border-radius: 25px;
            box-shadow: 
                12px 12px 24px var(--shadow-dark),
                -12px -12px 24px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .question-card {
            background: var(--bg-card);
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 
                10px 10px 20px var(--shadow-dark),
                -10px -10px 20px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin: 1.5rem 0;
            position: relative;
        }

        .question-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            border-radius: 24px 24px 0 0;
        }

        .question-card::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.6), transparent);
            border-radius: 24px 0 0 24px;
        }

        .answer-option {
            background: var(--bg-card);
            border: none;
            padding: 1.2rem 1.8rem;
            margin: 0.6rem 0;
            border-radius: 16px;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                4px 4px 8px var(--shadow-dark),
                -4px -4px 8px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .answer-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.7), transparent);
            border-radius: 16px 16px 0 0;
        }

        .answer-option:hover {
            transform: translateY(-1px);
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
        }

        .answer-option.selected {
            background: var(--green-soft);
            color: white;
            box-shadow: 
                inset 2px 2px 4px rgba(0, 0, 0, 0.1),
                4px 4px 8px var(--shadow-dark);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .answer-option.selected::before {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        }

        .progress-container {
            background: var(--bg-card);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 
                inset 6px 6px 12px var(--shadow-dark),
                inset -6px -6px 12px var(--shadow-light);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 1.5rem 0;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background: var(--shadow-dark);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 
                inset 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--blue-soft), var(--purple-soft));
            border-radius: 6px;
            transition: width 0.8s ease;
            box-shadow: 
                0 0 8px rgba(177, 156, 217, 0.4);
        }

        .btn-submit {
            background: var(--purple-soft);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                6px 6px 12px var(--shadow-dark),
                -6px -6px 12px var(--shadow-light);
            margin-top: 2rem;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 
                8px 8px 16px var(--shadow-dark),
                -8px -8px 16px var(--shadow-light);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .text-h1 { font-size: 2rem; }
            .text-h2 { font-size: 1.75rem; }
            .text-h3 { font-size: 1.25rem; }
            body { padding: 1rem; }
        }

        .success-message {
            background: var(--green-soft);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
            text-align: center;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
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

    <script>
        document.querySelector('meta[name="csrf-token"]').setAttribute('content', '{{ csrf_token() }}');
        
        const answers = {};
        const totalQuestions = {{ count($questions) }};
        let answeredQuestions = 0;

        document.querySelectorAll('.answer-option').forEach(button => {
            button.addEventListener('click', function() {
                const questionCard = this.closest('.question-card');
                const questionId = questionCard.dataset.questionId;
                const value = this.dataset.value;

                questionCard.querySelectorAll('.answer-option').forEach(opt => {
                    opt.classList.remove('selected');
                });

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

            // console.log('Answers to submit:', answers);
        });

    </script>
</body>
</html>