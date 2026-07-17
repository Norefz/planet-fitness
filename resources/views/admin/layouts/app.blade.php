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
  @include('partials.design-tokens')
  @include('partials.global-styles')

  {{-- Alpine.js — sebelumnya TIDAK dimuat di layout ini, sehingga notifikasi
       flash (x-data/x-show di header) tidak pernah tampil sama sekali. --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* Sidebar active state */
    .nav-item-active { background: rgba(29,158,117,.22); color: white; font-weight: 600; }
    .nav-item-active svg { color: #1d9e75; }
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

  {{-- Notifikasi toast terpusat (menggantikan flash block lama yang gagal
       tampil karena Alpine belum termuat) --}}
  @include('partials.toast-container')

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
