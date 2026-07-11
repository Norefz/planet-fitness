<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Dashboard') | Planet Fitness Admin</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  {{-- Tailwind CDN (ganti dengan vite build untuk production) --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
          colors: {
            primary:   { DEFAULT: '#1d9e75', dark: '#0f6e56', light: '#e1f5ee', mid: '#d0f0e4' },
            navy:      { DEFAULT: '#0a1628', mid: '#112240', soft: '#1a3a5c' },
          },
        }
      }
    }
  </script>
  <style>
    /* Sidebar active state & custom scrollbar */
    .nav-item-active { background: rgba(29,158,117,.22); color: white; font-weight: 600; }
    .nav-item-active svg { color: #1d9e75; }
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9px; }
    /* Stat card top bar */
    .stat-bar::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0;
      height: 3px; background: var(--bar-color); border-radius: 12px 12px 0 0;
    }
    /* Bar chart */
    .chart-bar { transition: opacity .15s; }
    .chart-bar:hover { opacity: .75; }
  </style>

  @stack('styles')
</head>
<body class="h-full font-sans bg-slate-100 text-slate-900 flex">

{{-- ═══════════════════════════════════════════════ SIDEBAR --}}
@include('admin.partials.sidebar')

{{-- ═══════════════════════════════════════════════ MAIN WRAP --}}
<div class="ml-60 flex-1 flex flex-col min-h-screen">

  {{-- ── Header ──────────────────────────────────────────── --}}
  @include('admin.partials.header')

  {{-- ── Page Content ──────────────────────────────────── --}}
  <main class="flex-1 p-7 overflow-auto">
    @yield('content')
  </main>

</div>

@stack('scripts')
</body>
</html>
