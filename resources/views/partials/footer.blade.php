{{--
    Partial: resources/views/partials/footer.blade.php
--}}

<footer class="relative mesh-dark noise-overlay overflow-hidden text-white">
    <div class="absolute -bottom-24 left-1/4 w-80 h-80 orb opacity-60 animate-orb-float-slow"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-[5%] pt-16 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-[1.4fr_1fr_1fr_1fr] gap-10 pb-12 border-b border-white/10">

            {{-- Brand column --}}
            <div>
                <div class="flex items-center gap-2.5 font-bold text-lg text-white mb-4">
                    <span class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18.816 13.58c2.292 2.138 3.546 4 3.092 4.9c-.745 1.46 -5.783 -.259 -11.255 -3.838c-5.47 -3.579 -9.304 -7.664 -8.56 -9.123c.464 -.91 2.926 -.444 5.803 .805"/>
                            <path d="M5 12a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/>
                        </svg>
                    </span>
                    Planet Fitness
                </div>
                <p class="text-sm text-white/50 leading-relaxed max-w-xs">
                    Ekosistem kebugaran digital — program latihan, log nutrisi, dan konsultasi mentor bersertifikat dalam satu platform.
                </p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/40 mb-4">Produk</p>
                <div class="flex flex-col gap-3">
                    <a href="#" class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors w-fit">Program Latihan</a>
                    <a href="#" class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors w-fit">Log Nutrisi</a>
                    <a href="#" class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors w-fit">Konsultasi Mentor</a>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/40 mb-4">Perusahaan</p>
                <div class="flex flex-col gap-3">
                    <a href="#" class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors w-fit">Tentang Aplikasi</a>
                    <a href="#" class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors w-fit">Daftar Mentor</a>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/40 mb-4">Legal</p>
                <div class="flex flex-col gap-3">
                    <a href="#" class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors w-fit">Syarat &amp; Ketentuan</a>
                    <a href="#" class="text-sm font-medium text-white/70 hover:text-[#4ade9e] transition-colors w-fit">Kebijakan Privasi</a>
                </div>
            </div>
        </div>

        <div class="pt-6 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-white/40">
                &copy; {{ date('Y') }} Planet Fitness &middot; Proyek Kelompok 5 FTI UKSW
            </p>
            <div class="flex items-center gap-2 text-xs text-white/40">
                <span class="w-1.5 h-1.5 rounded-full bg-primary-400"></span> Semua sistem berjalan normal
            </div>
        </div>
    </div>
</footer>
