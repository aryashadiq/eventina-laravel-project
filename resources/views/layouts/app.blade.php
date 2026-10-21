<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Eventina')</title>
        <!-- Google font: Archivo with its width axis, used condensed for poster headlines -->
        <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wdth,wght@0,62..125,100..900;1,62..125,100..900&display=swap" rel="stylesheet">
        <!-- Precompiled Tailwind + DaisyUI (npm run build); the version busts browser caches after a rebuild -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">
        <!-- Flowbite CSS (CDN) - ensures carousel styling works until assets are rebuilt -->
        <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" />

    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    <link rel="alternate icon" href="{{ asset('img/logo.svg') }}" type="image/svg+xml">
    <script>
        // small mobile menu toggle
        function toggleMenu() {
            const el = document.getElementById('topNav');
            if (!el) return;
            el.style.display = (el.style.display === 'block') ? 'none' : 'block';
        }
    </script>
    <!-- Note: Flowbite JS is bundled via Vite (`resources/js/app.js`) to avoid double-loading. -->
</head>

<body>
    <header class="site-header page-dots">
        <x-NavBar />
    </header>

    <main class="site-container" style="padding-top:20px">
        @yield('content')
    </main>

    <x-Footer />
</body>

</html>
