{{--
    Partial: resources/views/partials/footer.blade.php
--}}

<footer class="flex flex-wrap items-center justify-between gap-5
               px-[5%] py-10 border-t border-slate-200 bg-white">

    {{-- Logo --}}
    <div class="flex items-center gap-2.5 font-bold text-base text-slate-900">
        <span class="w-6 h-6 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
            <svg class="w-3.5 h-3.5 text-white" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M18.816 13.58c2.292 2.138 3.546 4 3.092 4.9c-.745 1.46 -5.783 -.259 -11.255 -3.838c-5.47 -3.579 -9.304 -7.664 -8.56 -9.123c.464 -.91 2.926 -.444 5.803 .805"/>
                <path d="M5 12a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/>
            </svg>
        </span>
        Planet Fitness
    </div>

    {{-- Links --}}
    <div class="flex flex-wrap gap-6">
        <a href="#" class="text-sm font-medium text-slate-500 hover:text-primary transition-colors duration-200 cursor-pointer">Tentang Aplikasi</a>
        <a href="#" class="text-sm font-medium text-slate-500 hover:text-primary transition-colors duration-200 cursor-pointer">Daftar Mentor</a>
        <a href="#" class="text-sm font-medium text-slate-500 hover:text-primary transition-colors duration-200 cursor-pointer">Syarat &amp; Ketentuan</a>
        <a href="#" class="text-sm font-medium text-slate-500 hover:text-primary transition-colors duration-200 cursor-pointer">Kebijakan Privasi</a>
    </div>

    {{-- Copyright --}}
    <p class="text-sm text-slate-400">
        &copy; {{ date('Y') }} Planet Fitness &middot; Proyek Kelompok 5 FTI UKSW
    </p>
</footer>
