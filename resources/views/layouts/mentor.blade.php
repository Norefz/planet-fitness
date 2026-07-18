<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title') · Planet Fitness Mentor</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.design-tokens')
  @include('partials.global-styles')

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    .scrollbar-thin::-webkit-scrollbar { height: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 9999px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
  </style>

  @stack('styles')
</head>
<body class="font-sans bg-slate-50 text-slate-900 antialiased min-h-screen relative">

  {{-- Faint decorative mesh sitting behind the entire app, Apple-keynote style --}}
  <div class="fixed inset-0 -z-10 mesh-light pointer-events-none"></div>

  <div x-data="{ mobileOpen: false, userMenuOpen: false }" @keydown.escape="mobileOpen = false; userMenuOpen = false" class="min-h-screen flex flex-col">

    <nav class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/70 relative">
      <div class="absolute inset-x-0 -bottom-px h-px bg-gradient-to-r from-transparent via-primary-400/40 to-transparent"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">

        <a href="{{ route('mentor.dashboard') }}" class="flex items-center gap-2.5 shrink-0 no-underline group">
          <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-[0_1px_0_0_rgba(255,255,255,0.3)_inset,0_4px_10px_-2px_rgba(29,158,117,0.5)] transition-transform duration-200 ease-out group-hover:scale-105 group-hover:-rotate-3">
            <x-mentor.icon name="dumbbell" class="w-4 h-4 text-white" />
          </div>
          <span class="text-[15px] font-bold tracking-tight hidden sm:inline">Planet Fitness</span>
        </a>

        @php
          $navItems = [
            ['route' => 'mentor.dashboard',        'label' => 'Dashboard',        'icon' => 'home'],
            ['route' => 'mentor.programs.index',   'label' => 'Program Latihan',  'icon' => 'dumbbell'],
            ['route' => 'mentor.statistics.index', 'label' => 'Statistik',        'icon' => 'bar-chart'],
            ['route' => 'mentor.bookings.index',   'label' => 'Konsultasi',       'icon' => 'calendar'],
          ];
        @endphp

        <div class="hidden md:flex items-center gap-0.5 bg-slate-100/70 rounded-xl p-1">
          @foreach ($navItems as $item)
            @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="relative flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-[13px] font-semibold transition-all duration-200 ease-out
                      {{ $isActive ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
              <x-mentor.icon :name="$item['icon']" class="w-3.5 h-3.5 {{ $isActive ? 'text-primary-600' : '' }}" />
              {{ $item['label'] }}
              @if ($isActive)
                <span class="absolute left-1/2 -bottom-[3px] -translate-x-1/2 w-4 h-[3px] rounded-full bg-gradient-to-r from-primary-400 to-primary-600"></span>
              @endif
            </a>
          @endforeach
        </div>

        <div class="flex items-center gap-2">

          <div class="relative" x-data @click.outside="userMenuOpen = false">
            <button @click="userMenuOpen = !userMenuOpen" type="button"
                    class="flex items-center gap-2 pl-1.5 pr-2.5 py-1.5 rounded-xl hover:bg-slate-100 transition-colors duration-150"
                    :class="userMenuOpen && 'bg-slate-100'">
              <x-mentor.avatar :name="auth()->user()->mentor->full_name" size="md" />
              <div class="hidden lg:block text-left leading-tight">
                <div class="text-[13px] font-semibold">{{ Str::limit(auth()->user()->mentor->full_name, 16) }}</div>
                <div class="text-[11px] text-slate-400">Mentor</div>
              </div>
              <x-mentor.icon name="chevron-down" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-150" x-bind:class="userMenuOpen && 'rotate-180'" />
            </button>

            <div x-show="userMenuOpen" x-cloak
                 x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="absolute right-0 mt-2 w-60 bg-white border border-slate-200 rounded-2xl shadow-dropdown overflow-hidden origin-top-right">
              <div class="px-4 py-3.5 border-b border-slate-100">
                <div class="text-sm font-semibold truncate">{{ auth()->user()->mentor->full_name }}</div>
                <div class="text-xs text-slate-400 truncate mt-0.5">{{ auth()->user()->email }}</div>
              </div>
              <div class="p-1.5">
                <a href="{{ route('mentor.profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                  <x-mentor.icon name="user" class="w-4 h-4 text-slate-400" /> Profil Saya
                </a>
                <a href="{{ route('mentor.statistics.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                  <x-mentor.icon name="bar-chart" class="w-4 h-4 text-slate-400" /> Statistik
                </a>
              </div>
              <div class="p-1.5 border-t border-slate-100">
                <form method="POST" action="{{ route('mentor.logout') }}">
                  @csrf
                  <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                    <x-mentor.icon name="log-out" class="w-4 h-4" /> Keluar
                  </button>
                </form>
              </div>
            </div>
          </div>

          <button @click="mobileOpen = !mobileOpen" type="button"
                  class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors">
            <x-mentor.icon name="menu" class="w-4 h-4" x-show="!mobileOpen" />
            <x-mentor.icon name="x" class="w-4 h-4" x-show="mobileOpen" x-cloak />
          </button>
        </div>
      </div>

      <div x-show="mobileOpen" x-cloak
           x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
           class="md:hidden border-t border-slate-100 px-4 py-3 flex flex-col gap-1 bg-white">
        @foreach ($navItems as $item)
          @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
          <a href="{{ route($item['route']) }}"
             class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors
                    {{ $isActive ? 'bg-primary-50 text-primary-700' : 'text-slate-500 hover:bg-slate-50' }}">
            <x-mentor.icon :name="$item['icon']" class="w-4 h-4" />
            {{ $item['label'] }}
          </a>
        @endforeach
      </div>
    </nav>

    @include('partials.toast-container')

    @if ($errors->any())
      <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 pt-6">
        <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5 text-sm text-red-700">
          <x-mentor.icon name="alert-triangle" class="w-4 h-4 mt-0.5 shrink-0" />
          <div class="space-y-0.5">
            <p class="font-semibold">Periksa kembali data yang kamu masukkan:</p>
            @foreach ($errors->all() as $e)
              <p>{{ $e }}</p>
            @endforeach
          </div>
        </div>
      </div>
    @endif

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 py-8 sm:py-10">
      @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 text-[13px] font-bold text-slate-700">
          <div class="w-5 h-5 rounded-md bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
            <x-mentor.icon name="dumbbell" class="w-3 h-3 text-white" />
          </div>
          Planet Fitness
        </div>
        <p class="text-xs text-slate-400">© 2026 Planet Fitness · Portal Mentor · Kelompok 5 FTI UKSW</p>
      </div>
    </footer>
  </div>

  @stack('scripts')
</body>
</html>
