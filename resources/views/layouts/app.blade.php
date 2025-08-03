<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-store" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Ganti vite dengan asset biasa -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mbti.css') }}">
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div class="min-h-screen bg-gray-100">
        <main>
            @yield('content')
        </main>
        @include('components.footer')
    </div>
    @if(session('set_tab_login'))
    <script>
        sessionStorage.setItem("hei_logged_in", "1");
    </script>
    @endif

    @if(session('skip_login_popup'))
    <script>
        sessionStorage.removeItem("hei_logged_in");
    </script>
    @endif
</body>
</html>
