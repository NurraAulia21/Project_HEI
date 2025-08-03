<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Personality Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mbti.css') }}">
</head>
<body class="dashboard-bg" style="background: linear-gradient(135deg, #FDFDFC 0%, #a78bfa 100%);">
    @include('components.navbar')

    <!-- Main Content -->
    <main class="main-content">
        <div class="main-card">
            <h1 class="main-title">It's so incredible to finally be understood.</h1>
            <p class="main-desc">
                Only 10 minutes to get a <span class="highlight">"freakishly accurate"</span> description of who you are and why you do things the way you do.
            </p>

            @if(Auth::check())
                <a href="{{ route('test') }}" class="main-action-btn">Go to Test</a>
            @else
                <button id="take-test-btn" class="main-action-btn">Take the Test</button>
            @endif

        </div>
        <p>Auth check: {{ Auth::check() ? 'true' : 'false' }}</p>
        <p>Session ID: {{ session()->getId() }}</p>
        <p>User: {{ Auth::check() ? Auth::user()->email : 'Guest' }}</p> 
    </main>

    @if(!Auth::check())
        <!-- Login Overlay -->
        <div id="login-overlay" class="overlay-bg hidden" style="margin-top: 60px;">
            <div class="overlay-card">
                <button id="close-login" class="overlay-close">&times;</button>
                <h2 class="overlay-title">Login</h2>

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
                    <button type="submit" class="form-btn">Login</button>
                </form>
                <p class="text-center" style="font-size: medium; text-align: center;">
                    Don't have an account?
                    <button id="show-register" class="link-btn">Sign Up</button>
                </p>

                <p class="or-divider">Or Login via Google</p>
                <a href="{{ route('google.login') }}" class="form-btn google-btn">
                    <img src="img/google.png" alt="Google Logo" style="width:30px;height:30px;margin-right:8px;">
                    <span>Login with Google</span>
                </a>
            </div>
        </div>

        <!-- Register Overlay -->
        <div id="register-overlay" class="overlay-bg hidden" style="margin-top: 60px;">
            <div class="overlay-card">
                <button id="close-register" class="overlay-close">&times;</button>
                <h2 class="overlay-title">Sign Up</h2>

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
                    <button type="submit" class="form-btn">Sign Up</button>
                </form>
                <p class="text-center" style="font-size: medium; text-align: center;">
                    Already have an account?
                    <button id="back-to-login" class="link-btn">Login</button>
                </p>

                <p class="or-divider">Or Sign Up via Google</p>
                <a href="{{ route('google.login') }}" class="form-btn google-btn">
                    <img src="img/google.png" alt="Google Logo" style="width:30px;height:30px;margin-right:8px;">
                    <span>Sign Up with Google</span>
                </a>
            </div>
        </div>
    @endif

    @include('components.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const takeTestBtn = document.getElementById("take-test-btn");
            const loginOverlay = document.getElementById("login-overlay");
            const registerOverlay = document.getElementById("register-overlay");

            // ✅ 1. Bersihkan storage DULU
            @if(session('clear_session_storage'))
                console.log("FLAG: clear_session_storage detected. Clearing...");
                localStorage.clear();
                sessionStorage.clear();
            @endif

            // ✅ 2. Lanjut ke pengecekan setelah storage dibersihkan
            const isLoggedInThisTab = sessionStorage.getItem("hei_logged_in");
            console.log("After clear(), hei_logged_in:", isLoggedInThisTab);
            if (isLoggedInThisTab) {
                window.location.href = "{{ route('test') }}";
            }

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('showLogin') === '1') {
                loginOverlay?.classList.remove("hidden");
            }

            @if(session('set_hei_logged_in'))
                sessionStorage.setItem("hei_logged_in", "1");
            @endif

            takeTestBtn?.addEventListener("click", function() {
                // Cek status login setelah klik tombol
                const isLoggedInThisTab = sessionStorage.getItem("hei_logged_in");
                if (!isLoggedInThisTab) {
                    loginOverlay?.classList.remove("hidden");
                } else {
                    window.location.href = "{{ route('test') }}";  // Langsung redirect ke halaman test jika sudah login
                }
            });


            document.getElementById('close-login')?.addEventListener('click', function() {
                loginOverlay.classList.add('hidden');
            });

            document.getElementById('close-register')?.addEventListener('click', function() {
                registerOverlay.classList.add('hidden');
                loginOverlay.classList.remove('hidden');
            });

            document.getElementById('show-register')?.addEventListener('click', function() {
                loginOverlay.classList.add('hidden');
                registerOverlay.classList.remove('hidden');
            });

            document.getElementById('back-to-login')?.addEventListener('click', function() {
                registerOverlay.classList.add('hidden');
                loginOverlay.classList.remove('hidden');
            });

            console.log("hei_logged_in (final):", sessionStorage.getItem("hei_logged_in"));
        });

        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        const isGuest = {{ Auth::guest() ? 'true' : 'false' }};
    </script>

</body>
</html>
