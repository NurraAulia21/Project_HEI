<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBTI Page</title>

    <link rel="stylesheet" href="{{ asset('css/mbti.css') }}">
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->

    @stack('styles')
</head>
<body>
    @include('components.navbar')
    @yield('content')

    @stack('scripts')
    @include('components.footer')
</body>
