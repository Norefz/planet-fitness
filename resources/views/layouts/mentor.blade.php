<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') | Planet Fitness Mentor</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
          colors: {
            primary: {
              DEFAULT: '#1d9e75',
              dark:    '#0f6e56',
              light:   '#e1f5ee',
            }
          },
          borderRadius: { xl: '1rem', '2xl': '1.25rem' }
        }
      }
    }
  </script>
  @stack('styles')
</head>
<body class="font-sans bg-slate-50 text-slate-900 min-h-screen flex flex-col">

  {{-- ═══ Top navigation ═══ --}}
  <nav class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">

      <a href="{{ route('mentor.dashboard') }}" class="flex items-center gap-2.5 shrink-0 no-underline">
        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-sm">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/>
          </svg>
        </div>
        <span class="text-base font-bold hidden sm:inline">Planet Fitness</span>
      </a>

      <div class="hidden md:flex items-center gap-1">
        @php
          $navItems = [
            ['route' => 'mentor.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
            ['route' => 'mentor.programs.index', 'label' => 'Program Latihan', 'icon' => 'dumbbell'],
            ['route' => 'mentor.bookings.index', 'label' => 'Konsultasi', 'icon' => 'calendar'],
            ['route' => 'mentor.profile.edit', 'label' => 'Profil', 'icon' => 'user'],
          ];
        @endphp
        @foreach ($navItems as $item)
          @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
          <a href="{{ route($item['route']) }}"
             class="flex items-center gap-2 px-3.5 py-2 rounded-lg text-sm font-semibold transition
                    {{ $isActive ? 'bg-primary-light text-primary-dark' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
            @include('mentor.partials.icon', ['name' => $item['icon'], 'class' => 'w-4 h-4'])
            {{ $item['label'] }}
          </a>
        @endforeach
      </div>

      <div class="flex items-center gap-3">
        <span class="hidden sm:inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-3 py-1.5 rounded-full">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
          Mentor
        </span>

        <div class="w-9 h-9 rounded-full bg-primary-light text-primary-dark flex items-center justify-center text-xs font-bold shrink-0">
          {{ auth()->user()->mentor->initials() }}
        </div>

        <form method="POST" action="{{ route('mentor.logout') }}">
          @csrf
          <button type="submit" title="Keluar" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          </button>
        </form>
      </div>
    </div>

    {{-- Mobile nav --}}
    <div class="md:hidden flex items-center gap-1 px-4 pb-3 overflow-x-auto">
      @foreach ($navItems as $item)
        @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition
                  {{ $isActive ? 'bg-primary-light text-primary-dark' : 'text-slate-500 bg-slate-100' }}">
          {{ $item['label'] }}
        </a>
      @endforeach
    </div>
  </nav>

  {{-- ═══ Flash messages ═══ --}}
  @if (session('success'))
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 pt-5">
      <div class="flex items-start gap-2.5 bg-primary-light border border-primary text-primary-dark rounded-xl px-4 py-3 text-sm font-medium">
        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="9 12 11 14 15 10"/><circle cx="12" cy="12" r="10"/></svg>
        {{ session('success') }}
      </div>
    </div>
  @endif

  @if ($errors->any())
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 pt-5">
      <div class="flex items-start gap-2.5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
      </div>
    </div>
  @endif

  {{-- ═══ Page content ═══ --}}
  <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 py-8">
    @yield('content')
  </main>

  <footer class="border-t border-slate-200 bg-white py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between flex-wrap gap-3">
      <div class="flex items-center gap-2 text-sm font-bold">
        <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center">
          <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/></svg>
        </div>
        Planet Fitness
      </div>
      <p class="text-xs text-slate-500">© 2026 Planet Fitness · Portal Mentor · Kelompok 5 FTI UKSW</p>
    </div>
  </footer>

  @stack('scripts')
</body>
</html>
