<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personality Test</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="dashboard-bg flex-center">
    <div class="main-card align-center">
        <h1 class="main-title">Welcome to the Personality Test Page!</h1>
        <p class="main-desc">You are now logged in and can start the test.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="main-action-btn">Logout</button>
        </form>
    </div>
</body>
</html>