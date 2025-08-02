<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MBTI Page</title>
    <link rel="stylesheet" href="{{ asset('css/mbti.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/test.css') }}"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
    @yield('styles')
</head>
<body>
    @include('components.navbar')
    <main style="padding-top: 50px">
        @yield('content')
    </main>
    @include('components.footer')
    @yield('scripts')
</body>
</html>