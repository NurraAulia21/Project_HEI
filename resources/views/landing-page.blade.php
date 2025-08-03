<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Personality Test</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mbti.css') }}">
</head>
<body class="dashboard-bg" style="background: linear-gradient(135deg, #FDFDFC 0%, #a78bfa 100%);">
    @section('content')
    @include('components.navbar')

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
    <div id="login-overlay" class="overlay-bg hidden" style="margin-top: 60px;">
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
                <img src="/img/google.png" alt="Google Logo" style="width:40px;height:40px;">
                <span style="text-decoration:none;color:#fff;font-weight:400">Login with Google</span>
            </a>
        </div>
    </div>
    <!-- Register Overlay -->
    <div id="register-overlay" class="overlay-bg hidden" style="margin-top: 60px;">
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
    </script>
    <!-- <section class="section-highlight">
        <div class="highlight-container">
            <div class="highlight-text">
                <h2 class="highlight-subtitle">PERSONALITY TYPES</h2>
                <h1 class="highlight-title">Understand others</h1>
                <p class="highlight-desc">
                    In our free type descriptions you’ll learn what really drives, inspires, and worries different personality types, helping you build more meaningful relationships.
                </p>
                <div class="highlight-buttons">
                    <a href="#" class="highlight-btn primary">Personality Types</a>
                </div>
            </div>
            <div class="highlight-image">
                <img src="/img/illustration-people.png" alt="Illustration" />
            </div>
        </div>
    </section> -->
    <section id="abouthei" class="section-highlight">
        <div class="highlight-container">
            <div class="highlight-text">
                <h2 class="highlight-subtitle">ABOUT HEI</h2>
                <h1 class="highlight-title">HEI (Harmony, Excellence, Integrity)</h1>
                <p class="highlight-desc">
                    HEI is a personality test designed to help individuals understand themselves and others better. It focuses on the core values of Harmony, Excellence, and Integrity, providing insights into personal strengths and areas for growth.
                </p>
                <p class="highlight-desc">
                    <b>Harmony</b> <br> Commitment Based on the Principles of Trust, Togetherness, Cooperation, Mutual Respect for Differences, Harmony and the Desire to Take Actions that Bring Good to Yourself and Others.
                </p>
                <p class="highlight-desc">
                    <b>Excellence</b> <br> The ability to use knowledge, skills, and attitudes to complete every job and task with the best quality for oneself and one's environment.
                </p>
                <p class="highlight-desc">
                    <b>Integrity</b> <br> Always maintain an attitude of following applicable norms and ethics by maintaining good relationships with others, being honest, trustworthy, independent, keeping promises, obeying, and upholding the truth.
                </p>
            </div>
            <div class="highlight-image">
                <img src="/img/hei-dummy.png" alt="Illustration" />
            </div>
        </div>
    </section>
    @include('components.footer')
</body>
</html>