{{--
    View  : resources/views/pages/home.blade.php
    Route : GET /  →  HomeController@index
--}}

@extends('layouts.app')

@section('title', 'Planet Fitness | Platform Kesehatan Digital')

@section('content')

{{-- =====================================================================
     HERO WRAPPER  — dark background + foto widhj
     ===================================================================== --}}
<div class="relative overflow-hidden bg-[#0a1a14]">

    {{-- Background image overlay --}}
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1600&q=80&auto=format&fit=crop')]
                bg-cover bg-[center_30%] opacity-[0.22] z-0 pointer-events-none"></div>

    {{-- Animated gradient orbs — subtle motion behind the hero content --}}
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -left-16 w-[420px] h-[420px] rounded-full blur-[110px] opacity-30
                    bg-primary animate-gradient-slow"></div>
        <div class="absolute top-1/3 -right-24 w-[380px] h-[380px] rounded-full blur-[110px] opacity-20
                    bg-[#4ade9e] animate-gradient-slow" style="animation-delay:-4s"></div>
    </div>

    {{-- Navbar (di dalam wrapper agar warnanya sesuai) --}}
    @include('partials.navbar')

    {{-- ── HERO SECTION ── --}}
    <section class="relative z-10 max-w-3xl mx-auto px-[5%] pt-20 pb-16 text-center">

        {{-- Badge --}}
        <div class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full mb-6
                    bg-white/15 border border-white/25 text-[#a7f3d0] text-[13px] font-semibold
                    backdrop-blur-md">
            <svg class="w-3.5 h-3.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 18a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2m0 -12a2 2 0 0 1 2 2a2 2 0 0 1 2 -2a2 2 0 0 1 -2 -2a2 2 0 0 1 -2 2m-7 12a6 6 0 0 1 6 -6a6 6 0 0 1 -6 -6a6 6 0 0 1 -6 6a6 6 0 0 1 6 6"/>
            </svg>
            Platform Kesehatan Digital Terpadu
        </div>

        {{-- Heading --}}
        <h1 class="text-4xl md:text-[44px] font-bold leading-[1.2] -tracking-[1px] mb-5 text-white">
            Personal Trainer, Ahli Gizi &amp;<br>
            <span class="text-[#4ade9e]">Asisten Medis</span> di Satu Aplikasi
        </h1>

        {{-- Subtext --}}
        <p class="text-base leading-7 text-white/75 mb-10">
            Planet Fitness membantu kamu merencanakan, memantau, dan mencapai target
            kesehatan — dengan program latihan, pencatatan nutrisi, dan konsultasi
            mentor bersertifikat.
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-wrap items-center justify-center gap-4 mb-14">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                           bg-primary text-white text-sm font-semibold shadow-sm
                           hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-md
                           transition-all duration-300 cursor-pointer">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3"/>
                    <path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3"/>
                    <path d="M14 9a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/>
                </svg>
                Mulai Sekarang
            </a>
            <a href="#demo" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                           bg-white/12 border border-white/30 text-white text-sm font-semibold no-underline
                           backdrop-blur-md hover:bg-white/20 transition-all duration-300 cursor-pointer">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 4v16l13 -8l-13 -8"/>
                </svg>
                Lihat Demo
            </a>
        </div>

        {{-- Stats Bar --}}
        <div class="grid grid-cols-3 divide-x divide-white/15 max-w-2xl mx-auto
                    border border-white/15 rounded-xl overflow-hidden
                    bg-white/12 backdrop-blur-xl shadow-sm">
            @foreach([
                ['10K+',  'Pengguna Aktif'],
                ['500+',  'Program Latihan'],
                ['120+',  'Mentor Ahli'],
            ] as $stat)
            <div class="py-6 text-center">
                <div class="text-3xl font-bold text-white">{{ $stat[0] }}</div>
                <div class="text-sm font-medium text-white/60 mt-1">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>

    </section>

    <hr class="relative z-10 border-t border-white/10 mx-[5%]">
</div>
{{-- /hero wrapper --}}


{{-- =====================================================================
     FITUR EKOSISTEM
     ===================================================================== --}}
<section id="fitur" class="max-w-[1200px] mx-auto px-[5%] py-20">

    <p class="text-[13px] font-bold tracking-[1.5px] uppercase text-primary-dark mb-3">
        Fitur Ekosistem
    </p>
    <h2 class="text-3xl font-bold -tracking-[0.5px] mb-4">
        Semua yang kamu butuhkan, terintegrasi.
    </h2>
    <p class="text-base text-slate-500 leading-7 max-w-xl mb-12">
        Dari pelacakan langkah kaki hingga konsultasi mentor profesional —
        Planet Fitness mengintegrasikan seluruh perjalanan kebugaranmu.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Fitur Cards --}}
        @php
        $features = [
            [
                'bg'    => '#e1f5ee',
                'color' => '#0f6e56',
                'title' => 'Pelacak Aktivitas',
                'desc'  => 'Sinkronisasi otomatis langkah kaki dari sensor smartphone dan catatan kalori harian.',
                'icon'  => '<path d="M11.007 5a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M4 17l5 1l.75 -1.5"/><path d="M15 21v-4l-4 -3l1 -6"/><path d="M7 12v-3l5 -1l3 3l3 1"/>',
            ],
            [
                'bg'    => '#faeeda',
                'color' => '#633806',
                'title' => 'Manajemen Nutrisi',
                'desc'  => 'Catat sarapan, makan siang, dan malam dengan detail gramasi makronutrisi.',
                'icon'  => '<path d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1"/><path d="M18.5 11c.351 -1.017 .426 -2.236 .5 -3.714v-1.286h-2.256c-2.83 0 -4.616 .804 -5.64 2.076"/><path d="M5.255 11.008a12.204 12.204 0 0 1 -.255 -2.008v-1h1.755c.98 0 1.801 .124 2.479 .35"/><path d="M8 8l1 -4l4 2.5"/><path d="M13 11v-.5a2.5 2.5 0 1 0 -5 0v.5"/>',
            ],
            [
                'bg'    => '#e6f1fb',
                'color' => '#185fa5',
                'title' => 'Program Latihan Video',
                'desc'  => 'Akses ratusan video panduan latihan terstruktur buatan mentor bersertifikat.',
                'icon'  => '<path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4"/><path d="M3 8a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2l0 -8"/>',
            ],
            [
                'bg'    => '#fbeaf0',
                'color' => '#72243e',
                'title' => 'Monitor Vital Tubuh',
                'desc'  => 'Pantau BMI, detak jantung, dan tanda vital untuk evaluasi kesehatan menyeluruh.',
                'icon'  => '<path d="M3 5a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1l0 -10"/><path d="M7 20h10"/><path d="M9 16v4"/><path d="M15 16v4"/><path d="M7 10h2l2 3l2 -6l1 3h3"/>',
            ],
        ];
        @endphp

        @foreach($features as $feat)
        <div class="bg-white border border-slate-200 rounded-xl2 p-8
                    hover:-translate-y-1.5 hover:shadow-lg hover:border-primary-light
                    transition-all duration-300 cursor-default">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 flex-shrink-0"
                 style="background: {{ $feat['bg'] }}">
                <svg class="w-6 h-6" style="color: {{ $feat['color'] }}"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    {!! $feat['icon'] !!}
                </svg>
            </div>
            <h3 class="text-[17px] font-semibold mb-2.5">{{ $feat['title'] }}</h3>
            <p class="text-sm text-slate-500 leading-relaxed">{{ $feat['desc'] }}</p>
        </div>
        @endforeach

    </div>
</section>

<hr class="border-t border-slate-200 mx-[5%]">


{{-- =====================================================================
     DASHBOARD REAL-TIME  (2 kolom)
     ===================================================================== --}}
<section id="demo" class="max-w-[1200px] mx-auto px-[5%] py-20 scroll-mt-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

        {{-- Kiri: teks + progress bars --}}
        <div>
            <p class="text-[13px] font-bold tracking-[1.5px] uppercase text-primary-dark mb-3">
                Dashboard Real-time
            </p>
            <h2 class="text-3xl font-bold -tracking-[0.5px] mb-4">
                Pantau progresmu dengan visual interaktif
            </h2>
            <p class="text-base text-slate-500 leading-7 mb-10">
                Dasbor grafis harian yang menampilkan kalori, langkah kaki, capaian
                latihan, dan progres kesehatan dengan antarmuka yang memanjakan mata.
            </p>

            {{-- Progress Bars --}}
            <div class="flex flex-col gap-5">
                @foreach([
                    ['label' => 'Kalori Terbakar',      'val' => '1.840 / 2.200 kkal', 'pct' => '83%',  'color' => '#1d9e75'],
                    ['label' => 'Langkah Kaki',         'val' => '7.420 / 10.000 steps','pct' => '74%', 'color' => '#378add'],
                    ['label' => 'Target Protein Harian','val' => '78g / 100g',          'pct' => '78%',  'color' => '#ef9f27'],
                ] as $bar)
                <div>
                    <div class="flex justify-between text-sm font-medium mb-2">
                        <span class="text-slate-500">{{ $bar['label'] }}</span>
                        <span class="text-slate-900 font-semibold">{{ $bar['val'] }}</span>
                    </div>
                    <div class="h-2 bg-slate-100 border border-slate-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700"
                             style="width: {{ $bar['pct'] }}; background: {{ $bar['color'] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Kanan: Dashboard Card --}}
        <div class="bg-white border border-slate-200 rounded-xl2 p-8 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold">Ringkasan Hari Ini</h4>
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-primary-light text-primary-dark">
                    Jum, 20 Jun 2026
                </span>
            </div>

            {{-- Mini stat cards --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                @foreach([
                    ['label' => 'Total Kalori',  'val' => '1.840', 'sub' => 'kkal terbakar',   'color' => '#1d9e75'],
                    ['label' => 'Total Langkah', 'val' => '7.4K',  'sub' => 'dari 10K target', 'color' => '#378add'],
                    ['label' => 'Skor BMI',      'val' => '22.4',  'sub' => 'Kategori Normal',  'color' => '#ef9f27'],
                    ['label' => 'Sesi Latihan',  'val' => '2',     'sub' => 'Selesai hari ini', 'color' => '#d4537e'],
                ] as $m)
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <p class="text-xs font-medium text-slate-500 mb-2">{{ $m['label'] }}</p>
                    <p class="text-2xl font-bold" style="color: {{ $m['color'] }}">{{ $m['val'] }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $m['sub'] }}</p>
                </div>
                @endforeach
            </div>

            <button class="w-full inline-flex items-center justify-center gap-2
                           px-4 py-2.5 rounded-xl text-sm font-semibold
                           border border-dashed border-slate-300 text-slate-600
                           hover:bg-slate-50 transition-colors duration-200 cursor-pointer">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5l0 14"/><path d="M5 12l14 0"/>
                </svg>
                Catat Makanan Baru
            </button>
        </div>

    </div>
</section>

<hr class="border-t border-slate-200 mx-[5%]">


{{-- =====================================================================
     WHY PLANET FITNESS  — background foto gym (opacity rendah)
     ===================================================================== --}}
<div class="relative overflow-hidden bg-[#f0faf6]">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1600&q=80&auto=format&fit=crop')]
                bg-cover bg-[center_40%] opacity-[0.07] z-0 pointer-events-none"></div>

    <section class="relative z-10 max-w-[1200px] mx-auto px-[5%] py-20">

        <p class="text-[13px] font-bold tracking-[1.5px] uppercase text-primary-dark mb-3">
            Mengapa Planet Fitness?
        </p>
        <h2 class="text-3xl font-bold -tracking-[0.5px] mb-4">
            Satu aplikasi untuk semua tujuan kesehatanmu
        </h2>
        <p class="text-base text-slate-500 leading-7 max-w-xl mb-12">
            Bukan sekadar penghitung kalori. Planet Fitness adalah ekosistem lengkap
            yang mendampingimu dari hari pertama hingga target tercapai.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @php
            $whyCards = [
                [
                    'accent'     => '#1d9e75',
                    'iconBg'     => '#e1f5ee',
                    'iconColor'  => '#0f6e56',
                    'proofBg'    => '#e1f5ee',
                    'proofColor' => '#0f6e56',
                    'title'      => 'Program yang disesuaikan untukmu',
                    'desc'       => 'Bukan program generik. Latihan dan target nutrisi dirancang sesuai data biometrik, level kebugaran, dan tujuanmu.',
                    'proof'      => '10.000+ member aktif',
                    'iconPath'   => '<path d="M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8"/><path d="M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8"/><path d="M17.5 16a3.5 3.5 0 0 0 0 -7h-.5"/><path d="M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0"/><path d="M6.5 16a3.5 3.5 0 0 1 0 -7h.5"/><path d="M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10"/>',
                    'proofIcon'  => '<path d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/>',
                ],
                [
                    'accent'     => '#378add',
                    'iconBg'     => '#e6f1fb',
                    'iconColor'  => '#185fa5',
                    'proofBg'    => '#e6f1fb',
                    'proofColor' => '#185fa5',
                    'title'      => 'Dibimbing mentor bersertifikat',
                    'desc'       => 'Akses langsung ke pelatih kebugaran dan ahli gizi tersertifikasi. Tanya, konsultasi, dan dapatkan feedback nyata.',
                    'proof'      => '120+ mentor ahli',
                    'iconPath'   => '<path d="M12 15a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/><path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5"/><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73"/><path d="M6 9l12 0"/><path d="M6 12l3 0"/><path d="M6 15l2 0"/>',
                    'proofIcon'  => '<path d="M6 9a6 6 0 1 0 12 0a6 6 0 1 0 -12 0"/><path d="M12 15l3.4 5.89l1.598 -3.233l3.598 .232l-3.4 -5.889"/><path d="M6.802 12l-3.4 5.89l3.598 -.233l1.598 3.232l3.4 -5.889"/>',
                ],
                [
                    'accent'     => '#ef9f27',
                    'iconBg'     => '#faeeda',
                    'iconColor'  => '#b45309',
                    'proofBg'    => '#faeeda',
                    'proofColor' => '#b45309',
                    'title'      => 'Progres yang terukur & transparan',
                    'desc'       => 'Dashboard real-time menampilkan kalori, langkah kaki, BMI, dan sesi latihan. Kamu selalu tahu seberapa dekat dengan target.',
                    'proof'      => 'Rata-rata -4kg dalam 8 minggu',
                    'iconPath'   => '<path d="M4 19l16 0"/><path d="M4 15l4 -6l4 2l4 -5l4 4"/>',
                    'proofIcon'  => '<path d="M3 17l6 -6l4 4l8 -8"/><path d="M14 7l7 0l0 7"/>',
                ],
                [
                    'accent'     => '#d4537e',
                    'iconBg'     => '#fbeaf0',
                    'iconColor'  => '#be185d',
                    'proofBg'    => '#fbeaf0',
                    'proofColor' => '#be185d',
                    'title'      => 'Membership yang jelas',
                    'desc'       => 'Buat akun tanpa kartu kredit, lalu aktifkan membership Rp60.000 per bulan untuk membuka seluruh fitur.',
                    'proof'      => 'Mulai dalam 60 detik',
                    'iconPath'   => '<path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/>',
                    'proofIcon'  => '<path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3"/><path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3"/><path d="M14 9a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/>',
                ],
            ];
            @endphp

            @foreach($whyCards as $i => $card)
            <div class="relative bg-white border border-slate-200 rounded-xl2 pt-8 px-6 pb-6 overflow-hidden
                        hover:-translate-y-1.5 hover:shadow-lg hover:border-primary-light
                        transition-all duration-300 cursor-default reveal-on-scroll"
                 style="transition-delay: {{ $i * 80 }}ms">
                {{-- Top accent line --}}
                <div class="absolute top-0 left-0 right-0 h-[3px] rounded-t-xl2"
                     style="background: {{ $card['accent'] }}"></div>

                {{-- Icon --}}
                <div class="w-[52px] h-[52px] rounded-xl flex items-center justify-center mb-5"
                     style="background: {{ $card['iconBg'] }}">
                    <svg class="w-6 h-6" style="color: {{ $card['iconColor'] }}"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        {!! $card['iconPath'] !!}
                    </svg>
                </div>

                <h3 class="text-[17px] font-bold mb-2.5 leading-snug">{{ $card['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-7">{{ $card['desc'] }}</p>

                {{-- Proof badge --}}
                <div class="inline-flex items-center gap-1.5 mt-4 px-3 py-1.5 rounded-full text-xs font-bold"
                     style="background: {{ $card['proofBg'] }}; color: {{ $card['proofColor'] }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        {!! $card['proofIcon'] !!}
                    </svg>
                    {{ $card['proof'] }}
                </div>
            </div>
            @endforeach

        </div>
    </section>
</div>
{{-- /why wrapper --}}


{{-- =====================================================================
     TESTIMONIALS — social proof dari member & mentor
     ===================================================================== --}}
<section class="max-w-[1200px] mx-auto px-[5%] py-16">

    <div class="text-center max-w-xl mx-auto mb-12 reveal-on-scroll">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full mb-4
                     bg-primary-light text-primary-dark text-xs font-bold uppercase tracking-wide">
            Kata Mereka
        </span>
        <h2 class="text-3xl font-bold -tracking-[0.5px] mb-3">Dipercaya ribuan orang</h2>
        <p class="text-slate-500 text-[15px] leading-7">
            Cerita nyata dari member dan mentor yang sudah merasakan hasilnya.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        @php
            $testimonials = [
                [
                    'name' => 'Dinda Ayu Ramadhani',
                    'role' => 'Member sejak 2024',
                    'initials' => 'DR',
                    'color' => '#1D9E75',
                    'quote' => 'Log nutrisinya gampang banget dipakai tiap hari. Berat badan turun 6kg dalam 3 bulan tanpa harus mikir keras soal kalori.',
                ],
                [
                    'name' => 'Muhammad Fajar Nugroho',
                    'role' => 'Member sejak 2023',
                    'initials' => 'FN',
                    'color' => '#2563eb',
                    'quote' => 'Fitur konsultasi mentornya nolong banget. Bisa tanya soal program latihan langsung tanpa perlu janjian ribet ke gym.',
                ],
                [
                    'name' => 'Sarah Kusuma Wardani',
                    'role' => 'Mentor bersertifikat',
                    'initials' => 'SW',
                    'color' => '#db2777',
                    'quote' => 'Dashboard mentornya rapi, gampang pantau progres semua member sekaligus. Jadwal booking juga jadi jauh lebih teratur.',
                ],
            ];
        @endphp

        @foreach($testimonials as $i => $t)
        <div class="bg-white border border-slate-200 rounded-xl2 p-7 hover-lift reveal-on-scroll"
             style="transition-delay: {{ $i * 100 }}ms">
            {{-- Star rating --}}
            <div class="flex items-center gap-0.5 mb-4" aria-label="Rating 5 dari 5 bintang">
                @for ($s = 0; $s < 5; $s++)
                    <svg class="w-4 h-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 0 0 .95.69h4.163c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 0 0-.363 1.118l1.287 3.957c.3.922-.755 1.688-1.538 1.118l-3.367-2.446a1 1 0 0 0-1.176 0l-3.367 2.446c-.783.57-1.838-.196-1.538-1.118l1.287-3.957a1 1 0 0 0-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 0 0 .95-.69l1.286-3.958z"/>
                    </svg>
                @endfor
            </div>

            <p class="text-sm text-slate-600 leading-7 mb-6">&ldquo;{{ $t['quote'] }}&rdquo;</p>

            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-full flex items-center justify-center text-[13px] font-bold text-white flex-shrink-0"
                      style="background: {{ $t['color'] }}">
                    {{ $t['initials'] }}
                </span>
                <div>
                    <div class="text-sm font-semibold text-slate-900">{{ $t['name'] }}</div>
                    <div class="text-xs text-slate-400">{{ $t['role'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
{{-- /testimonials --}}


{{-- =====================================================================
     CTA SECTION — dark green + gym photo overlay
     ===================================================================== --}}
<div class="relative overflow-hidden bg-primary-dark rounded-xl2 mx-[5%] my-10
            shadow-md text-center px-8 py-16">
    {{-- bg image overlay --}}
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=1400&q=80&auto=format&fit=crop')]
                bg-cover bg-[center_25%] opacity-[0.18] z-0 pointer-events-none rounded-xl2"></div>

    <div class="relative z-10">
        <h2 class="text-3xl font-bold -tracking-[0.5px] text-white mb-4">
            Mulai perjalanan kesehatanmu hari ini
        </h2>
        <p class="text-base text-white/80 max-w-md mx-auto mb-8 leading-7">
            Buat akun gratis, lalu aktifkan membership bulanan untuk membuka seluruh
            program latihan, log nutrisi, dan konsultasi mentor.
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl
                       bg-white text-primary-dark text-sm font-semibold shadow-sm
                       hover:bg-slate-50 transition-colors duration-200 cursor-pointer">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3"/>
                <path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3"/>
                <path d="M14 9a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"/>
            </svg>
            Daftar &amp; Aktifkan Membership
        </a>
    </div>
</div>

{{-- Footer --}}
@include('partials.footer')

@endsection
