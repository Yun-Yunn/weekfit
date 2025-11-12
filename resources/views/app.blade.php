<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WeekFit</title>

    <!-- Tailwind + JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white flex flex-col items-center justify-center min-h-screen">
    <div class="text-center w-full max-w-3xl p-6">
        @yield('content')
    </div>
</body>
</html>
