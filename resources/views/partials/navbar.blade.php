{{--
    Partial: resources/views/partials/navbar.blade.php
    Digunakan di setiap halaman via @include('partials.navbar')
--}}

{{-- =====================================================================
     NAVBAR — sticky, glassmorphism di atas hero gelap
     ===================================================================== --}}
<nav
    id="pf-navbar"
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
        <a href="#fitur"
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200 cursor-pointer">
            Fitur Utama
        </a>
        <a href=""
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200">
            Program Latihan
        </a>
        <a href=""
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200">
            Log Nutrisi
        </a>
        <a href=""
           class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors duration-200">
            Konsultasi Mentor
        </a>
    </div>

    {{-- Auth Buttons --}}
    <div class="flex items-center gap-3">
        <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                       bg-white/10 border border-white/30 text-white hover:bg-white/20
                       backdrop-blur-md transition-all duration-200 cursor-pointer">
            Masuk
        </button>
        <button class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold
                       bg-primary text-white hover:bg-primary-dark shadow-sm
                       hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 cursor-pointer">
            Daftar Gratis
        </button>
    </div>
</nav>
