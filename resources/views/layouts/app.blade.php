<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Planet Fitness | Platform Kesehatan Digital')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    {{-- Tailwind CSS CDN (ganti dengan Vite + npm di production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.design-tokens')
    @include('partials.global-styles')

    {{-- Vite assets (aktifkan saat production, nonaktifkan jika pakai CDN) --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="font-sans bg-white text-slate-900 antialiased">

    {{-- Notifikasi toast global + pesan validasi form --}}
    @include('partials.toast-container')

    @if ($errors->any())
        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-[190] w-[calc(100%-2rem)] max-w-lg sm:left-auto sm:right-6 sm:translate-x-0">
            <div class="flex items-start gap-3 bg-white border border-red-200 rounded-2xl shadow-dropdown px-4 py-3.5 text-sm text-red-700">
                <svg class="w-4 h-4 mt-0.5 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4m0 4h.01"/>
                </svg>
                <div class="space-y-0.5">
                    <p class="font-semibold text-red-800">Periksa kembali data yang kamu masukkan:</p>
                    @foreach ($errors->all() as $e)
                        <p>{{ $e }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @yield('content')

    @stack('scripts')

    {{-- Scroll-reveal: fades/slides elements with .reveal-on-scroll into place --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const targets = document.querySelectorAll('.reveal-on-scroll');
            if (!('IntersectionObserver' in window) || targets.length === 0) {
                targets.forEach(el => el.classList.add('is-visible'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
            targets.forEach(el => io.observe(el));
        });
    </script>
</body>
</html>
