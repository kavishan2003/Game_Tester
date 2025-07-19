<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Game | tester</title>

    {{-- favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon1.png') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    <!-- Styles / Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif

</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen text-gray-800 antialiased font-sans">

    @livewire('game-tester')
    {{-- @yield('content') --}}

</body>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>


</html>
