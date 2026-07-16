<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Planet Fitness | Platform Kesehatan Digital')</title>

    {{-- Tailwind CSS CDN (ganti dengan Vite + npm di production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#1D9E75',
                            dark:    '#0F6E56',
                            light:   '#E1F5EE',
                        },
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    borderRadius: {
                        'xl2': '20px',
                    },
                },
            },
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Vite assets (aktifkan saat production, nonaktifkan jika pakai CDN) --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="font-sans bg-white text-slate-900 antialiased">

    @yield('content')

    @stack('scripts')
</body>
</html>
