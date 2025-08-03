<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
</head>
<body class="dashboard-bg" style="background: linear-gradient(135deg, #FDFDFC 0%, #a78bfa 100%);">
    <div class="overlay-bg" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <div class="overlay-card" style="width: 400px;">
            <h2 class="overlay-title">Admin Login</h2>
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="form-btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
