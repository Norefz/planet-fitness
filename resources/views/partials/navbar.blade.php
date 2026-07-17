{{--
    Partial: resources/views/partials/navbar.blade.php
    Digunakan di setiap halaman via @include('partials.navbar')
--}}

{{-- =====================================================================
     NAVBAR — sticky, glassmorphism di atas hero gelap
     ===================================================================== --}}
<nav
    id="pf-navbar"
    x-data="{ mobileOpen: false }"
    class="flex items-center justify-between px-[5%] py-4 sticky top-0 z-50
           border-b border-white/10 bg-[rgba(10,26,20,0.6)] backdrop-blur-xl
           transition-all duration-300"
>
    {{-- Logo --}}
    <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-lg text-white no-underline">
        <span class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shadow-sm flex-shrink-0">
            {{-- Icon Planet --}}
            <svg class="w-[18px] h-[18px] text-white" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M18.816 13.58c2.292 2.138 3.546 4 3.092 4.9c-.745 1.46 -5.783 -.259 -11.255 -3.838c-5.47 -3.579 -9.304 -7.664 -8.56 -9.123c.464 -.91 2.926 -.444 5.803 .805"/>
                <path d="M5 12a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/>
            </svg>
        </span>
        Planet Fitness
    </a>

    {{-- Nav Links (hidden di mobile) --}}
    <div class="hidden md:flex items-center gap-8">
        <a href="{{ route('home') }}"
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200 cursor-pointer">
             Fitur Utama
        </a>
        <a href="{{ Auth::check() ? route('member.programs.index') : route('programs.preview') }}"
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200">
             Program Latihan
        </a>
        <a href="{{ route('log-nutrisi') }}"
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200">
             Log Nutrisi
        </a>
        <a href="{{ Auth::check() ? route('member.konsultasi') : route('konsultasi.preview') }}"
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200">
             Konsultasi Mentor
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         AUTH AREA — beda tampilan tergantung status login
         ═══════════════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-3">

        @guest
            {{-- ── Belum login: tombol Masuk & Daftar ── --}}
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                      bg-white/10 border border-white/30 text-white hover:bg-white/20
                      backdrop-blur-md transition-all duration-200 cursor-pointer no-underline">
                Masuk
            </a>
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                      bg-primary text-white hover:bg-primary-dark shadow-sm
                      hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 cursor-pointer no-underline">
                Daftar Gratis
            </a>
        @else
            {{-- ── Sudah login: avatar + nama akun + dropdown ── --}}
            @php
                // Ambil nama sesuai role (member/mentor punya tabel profil sendiri)
                $displayName = match(auth()->user()->role) {
                    'mentor' => auth()->user()->mentor?->full_name,
                    default  => auth()->user()->member?->full_name,
                } ?? explode('@', auth()->user()->email)[0];

                $initials = collect(explode(' ', $displayName))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->take(2)
                    ->join('');

                // Fixed: Correctly maps variables to the matched routing patterns
                $dashboardRoute = match(auth()->user()->role) {
                    'mentor' => route('mentor.dashboard'),
                    default  => route('member.dashboard'),
                };
            @endphp

            <div class="relative" x-data="{ open: false }" @click.outside="open = false">

                {{-- Trigger button --}}
                <button @click="open = !open"
                        class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl
                               bg-white/10 border border-white/20 hover:bg-white/15
                               backdrop-blur-md transition-all duration-200 cursor-pointer">

                    {{-- Avatar: pakai foto Google kalau ada, fallback inisial --}}
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="{{ $displayName }}"
                             class="w-7 h-7 rounded-full object-cover flex-shrink-0 border border-white/20" />
                    @else
                        <span class="w-7 h-7 rounded-full bg-primary flex items-center justify-center
                                     text-[11px] font-bold text-white flex-shrink-0">
                            {{ $initials }}
                        </span>
                    @endif

                    <span class="text-sm font-semibold text-white max-w-[120px] truncate">
                        {{ $displayName }}
                    </span>

                    <svg class="w-3.5 h-3.5 text-white/60 transition-transform duration-200 flex-shrink-0"
                         :class="{ 'rotate-180': open }"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                {{-- Dropdown menu --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="absolute right-0 mt-2 w-56 rounded-xl bg-white border border-slate-200
                            shadow-lg overflow-hidden z-50"
                     style="display: none;">

                    {{-- User info header --}}
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="text-sm font-semibold text-slate-900 truncate">{{ $displayName }}</div>
                        <div class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</div>

                        {{-- Fixed Badge styling to seamlessly distinguish Mentor vs Member --}}
                        <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                     {{ auth()->user()->role === 'mentor' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                            {{ auth()->user()->role === 'mentor' ? 'Mentor' : 'Member' }}
                        </span>
                    </div>

                    {{-- Menu items --}}
                    <div class="py-1">
                        <a href="{{ $dashboardRoute }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50
                                  transition-colors duration-150 no-underline">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                            </svg>
                            Dashboard
                        </a>

                        <a href="#"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50
                                  transition-colors duration-150 no-underline">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Profil Saya
                        </a>

                        <a href="#"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50
                                  transition-colors duration-150 no-underline">
                            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"/>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1 -2.83 2.83l-.06-.06a1.65 1.65 0 0 0 -1.82-.33 1.65 1.65 0 0 0 -1 1.51V21a2 2 0 0 1 -4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0 -1.82.33l-.06.06a2 2 0 1 1 -2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0 -1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0 -.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0 -.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0 -1.51 1z"/>
                            </svg>
                            Pengaturan
                        </a>
                    </div>

                    {{-- Logout --}}
                    <div class="py-1 border-t border-slate-100">
                        @php
                            $logoutRoute = auth()->user()->role === 'mentor' ? 'mentor.logout' : 'member.logout';
                        @endphp

                        <form method="POST" action="{{ route($logoutRoute) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600
                                           hover:bg-red-50 transition-colors duration-150 cursor-pointer">
                                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
                                    <path d="M9 12h12l-3 -3m0 6l3 -3"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endguest

    </div>

    {{-- Hamburger toggle — mobile only --}}
    <button
        @click="mobileOpen = !mobileOpen"
        class="md:hidden ml-2 flex items-center justify-center w-9 h-9 rounded-lg
               bg-white/10 border border-white/20 text-white hover:bg-white/20
               transition-colors duration-200 flex-shrink-0"
        aria-label="Buka menu navigasi"
        :aria-expanded="mobileOpen"
    >
        <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
        <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>

    {{-- Mobile nav panel --}}
    <div
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.outside="mobileOpen = false"
        class="md:hidden absolute top-full left-0 right-0 bg-[#0a1a14] border-b border-white/10
               shadow-dropdown flex flex-col px-[5%] py-3 gap-1"
    >
        <a href="{{ route('home') }}" class="px-2 py-3 text-sm font-medium text-white/80 hover:text-[#4ade9e] rounded-lg no-underline">Fitur Utama</a>
        <a href="{{ Auth::check() ? route('member.programs.index') : route('programs.preview') }}" class="px-2 py-3 text-sm font-medium text-white/80 hover:text-[#4ade9e] rounded-lg no-underline">Program Latihan</a>
        <a href="{{ route('log-nutrisi') }}" class="px-2 py-3 text-sm font-medium text-white/80 hover:text-[#4ade9e] rounded-lg no-underline">Log Nutrisi</a>
        <a href="{{ Auth::check() ? route('member.konsultasi') : route('konsultasi.preview') }}" class="px-2 py-3 text-sm font-medium text-white/80 hover:text-[#4ade9e] rounded-lg no-underline">Konsultasi Mentor</a>
    </div>
</nav>
