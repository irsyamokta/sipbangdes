<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.svg') }}">

    <title inertia>{{ config('app.name', 'Sipbangdes') }}</title>

    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>

    <!-- Scripts -->
    @routes
    @env('local')
        @viteReactRefresh
    @endenv
    @vite('resources/js/app.tsx')
    @inertiaHead
</head>

<body class="antialiased">
    @inertia
</body>

</html>
