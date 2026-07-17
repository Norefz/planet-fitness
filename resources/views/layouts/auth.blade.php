<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') | Planet Fitness</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  @include('partials.design-tokens')
  @include('partials.global-styles')
</head>
<body class="font-sans bg-ink-950 text-slate-900 min-h-screen antialiased">

  {{-- Flash messages --}}
  @if (session('success'))
    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
      <div class="flex items-start gap-3 bg-primary-light border border-primary text-primary-dark rounded-xl px-4 py-3 shadow-md text-sm font-medium">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="9 12 11 14 15 10"/><circle cx="12" cy="12" r="10"/></svg>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  <div class="min-h-screen flex flex-col lg:flex-row">

    {{-- ═══════════ Panel kiri — hero gelap dengan orb 3D mengambang, ala Apple.com ═══════════ --}}
    <div class="relative hidden lg:flex lg:w-[46%] xl:w-[42%] mesh-dark noise-overlay overflow-hidden flex-col justify-between px-12 xl:px-16 py-14">

        {{-- Orbs mengambang --}}
        <div class="absolute -top-24 -left-20 w-96 h-96 orb animate-orb-float-slow"></div>
        <div class="absolute bottom-[-6rem] right-[-4rem] w-72 h-72 orb animate-orb-float" style="animation-delay: -6s;"></div>
        <div class="absolute top-1/2 left-1/2 w-[140%] aspect-square rounded-full border border-white/[0.06] -translate-x-1/2 -translate-y-1/2 animate-orb-spin-slow"></div>
        <div class="absolute top-1/2 left-1/2 w-[100%] aspect-square rounded-full border border-white/[0.05] -translate-x-1/2 -translate-y-1/2"></div>

        <a href="{{ url('/') }}" class="relative z-10 flex items-center gap-2.5 no-underline w-fit">
            <div class="w-9 h-9 rounded-full bg-white/10 border border-white/20 backdrop-blur-md flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/>
                </svg>
            </div>
            <span class="text-lg font-bold text-white">Planet Fitness</span>
        </a>

        <div class="relative z-10 max-w-md">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary-300/80 mb-5">Platform Kesehatan Digital</p>
            <h1 class="display-heading text-5xl xl:text-[3.4rem] font-extrabold text-white mb-6">
                Bentuk tubuh<br class="hidden xl:block" /> terbaikmu, <span class="text-gradient-light">satu sesi</span> pada satu waktu.
            </h1>
            <p class="text-white/60 text-[15px] leading-relaxed">
                Program latihan bersertifikat, log nutrisi harian, dan konsultasi video langsung dengan mentor — semua dalam satu ekosistem.
            </p>
        </div>

        <div class="relative z-10 flex items-center gap-8 text-white/50 text-xs font-semibold">
            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-400"></span> 500+ Program</div>
            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-400"></span> Mentor Bersertifikat</div>
            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-400"></span> Live Konsultasi</div>
        </div>
    </div>

    {{-- ═══════════ Panel kanan — form, tipografi besar & lapang ═══════════ --}}
    <div class="flex-1 flex items-center justify-center px-4 sm:px-6 py-12 lg:py-10 bg-slate-50 relative">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary-400 via-primary-600 to-navy lg:hidden"></div>
        @yield('content')
    </div>

  </div>

</body>
</html>
