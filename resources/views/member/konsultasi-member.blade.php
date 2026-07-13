@extends('layouts.app')

@section('title', 'Konsultasi Mentor - Planet Fitness')

@section('content')
{{-- Bungkus Utama Konten (Menjaga Responsivitas & Lebar Layar) --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ══════════════════════════════════════════
         PAGE HEADER
         ══════════════════════════════════════════ --}}
    <div class="mb-8 md:mb-10">
        <div class="text-[11px] font-bold text-primary tracking-widest uppercase mb-2">Penjadwalan & Interaksi</div>
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 mb-2">Konsultasi Mentor</h1>
        <p class="text-slate-500 text-xs md:text-sm max-w-2xl leading-relaxed">
            Booking sesi video konsultasi langsung dengan mentor bersertifikat — dari pengajuan jadwal hingga sesi selesai.
        </p>
    </div>

    {{-- ══════════════════════════════════════════
         SECTION: CARI MENTOR
         ══════════════════════════════════════════ --}}
    <div class="mb-10 md:mb-12">
        <h3 class="text-base md:text-lg font-bold text-slate-900 mb-5 flex items-center gap-2">
            <i class="ti ti-users text-primary text-lg"></i> Cari Mentor
        </h3>

        {{-- Grid responsif: 1 kolom di HP, 2 kolom di Tablet, 3 kolom di Desktop --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            <!-- Card Mentor 1 -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 group flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-primary-light text-primary-dark flex items-center justify-center text-base md:text-lg font-bold shrink-0 shadow-sm">RA</div>
                        <div class="min-w-0">
                            <h4 class="text-sm md:text-base font-bold text-slate-900 truncate">Rini Andini</h4>
                            <p class="text-xs text-slate-500 mt-0.5 break-words">Pelatih Kebugaran & Penurunan Berat Badan</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center mb-4">
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-primary bg-primary-light px-2.5 py-0.5 rounded-full">
                            <i class="ti ti-certificate"></i> Bersertifikat
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                            <i class="ti ti-star-filled text-amber-500"></i>
                            <strong class="text-slate-900">4.9</strong> <span class="text-slate-400">(212 sesi)</span>
                        </span>
                    </div>
                </div>
                <button onclick="selectMentor('Rini Andini')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs md:text-sm font-semibold bg-primary text-white hover:bg-primary-dark transition-colors cursor-pointer border-none shadow-sm">
                    <i class="ti ti-calendar-plus text-base"></i> Booking Konsultasi
                </button>
            </div>

            <!-- Card Mentor 2 -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 md:p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300 group flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-base md:text-lg font-bold shrink-0 shadow-sm">DP</div>
                        <div class="min-w-0">
                            <h4 class="text-sm md:text-base font-bold text-slate-900 truncate">Dimas Pratama</h4>
                            <p class="text-xs text-slate-500 mt-0.5 break-words">Spesialis Kekuatan & Hipertrofi Otot</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center mb-4">
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full">
                            <i class="ti ti-certificate"></i> Bersertifikat
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                            <i class="ti ti-star-filled text-amber-500"></i>
                            <strong class="text-slate-900">4.8</strong> <span class="text-slate-400">(164 sesi)</span>
                        </span>
                    </div>
                </div>
                <button onclick="selectMentor('Dimas Pratama')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs md:text-sm font-semibold bg-primary text-white hover:bg-primary-dark transition-colors cursor-pointer border-none shadow-sm">
                    <i class="ti ti-calendar-plus text-base"></i> Booking Konsultasi
                </button>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════
         SECTION: FORM JADWAL BOOKING
         ══════════════════════════════════════════ --}}
    <div class="mb-10 md:mb-12" id="booking-section">
        <h3 class="text-base md:text-lg font-bold text-slate-900 mb-5 flex items-center gap-2">
            <i class="ti ti-calendar text-primary text-lg"></i> Jadwalkan Sesi — <span id="selectedMentorName" class="text-primary font-extrabold">Rini Andini</span>
        </h3>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 md:p-8 shadow-[0_1px_3px_rgb(0_0_0/.05)]">

            <!-- Pilihan Tanggal -->
            <div class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Pilih Tanggal</div>
            <div class="flex flex-wrap gap-2.5 mb-6">
                <button class="date-btn px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border transition-all cursor-pointer bg-primary border-primary text-white active-date">Sen, 22 Jun</button>
                <button class="date-btn px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border transition-all cursor-pointer bg-white border-slate-200 text-slate-700 hover:border-primary">Sel, 23 Jun</button>
                <button class="date-btn px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border transition-all cursor-pointer bg-white border-slate-200 text-slate-700 hover:border-primary">Rab, 24 Jun</button>
            </div>

            <!-- Pilihan Jam -->
            <div class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Pilih Waktu Tersedia</div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
                <div class="border rounded-xl py-3 text-center text-xs md:text-sm font-bold bg-slate-50 text-slate-400 border-slate-200 line-through cursor-not-allowed">09:00</div>
                <div class="booking-slot border rounded-xl py-3 text-center text-xs md:text-sm font-bold bg-white border-slate-200 text-slate-700 cursor-pointer hover:border-primary transition-all">10:00</div>
                <div class="booking-slot border rounded-xl py-3 text-center text-xs md:text-sm font-bold bg-primary border-primary text-white cursor-pointer transition-all active-slot">13:00</div>
                <div class="booking-slot border rounded-xl py-3 text-center text-xs md:text-sm font-bold bg-white border-slate-200 text-slate-700 cursor-pointer hover:border-primary transition-all">14:00</div>
            </div>

            <!-- Tombol Aksi Bersyarat (Guest vs Member) -->
            @auth
                {{-- Jika sudah login sebagai member --}}
                <button class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs md:text-sm font-bold bg-primary text-white hover:bg-primary-dark transition-all cursor-pointer border-none shadow-md">
                    <i class="ti ti-send text-base md:text-lg"></i> Ajukan Permintaan Booking
                </button>
            @endauth

            @guest
                {{-- Jika belum login / pengakses umum --}}
                <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-xs md:text-sm font-bold bg-amber-500 text-white hover:bg-amber-600 transition-all cursor-pointer border-none text-center no-underline shadow-md">
                    <i class="ti ti-lock text-base md:text-lg"></i> Login untuk Ajukan Booking
                </a>
            @endguest
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         SECTION: LIST SESI AKTIF (HANYA UNTUK MEMBER)
         ══════════════════════════════════════════ --}}
    @auth
    <div>
        <h3 class="text-base md:text-lg font-bold text-slate-900 mb-5 flex items-center gap-2">
            <i class="ti ti-device-laptop text-primary text-lg"></i> Sesi Konsultasimu
        </h3>
        <div class="flex flex-col gap-4">

            <!-- Baris Sesi 1 (Dikonfirmasi) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 shadow-[0_1px_2px_rgb(0_0_0/.03)]">
                <div class="flex items-start md:items-center gap-4 min-w-0">
                    <div class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-primary-light text-primary-dark flex items-center justify-center text-sm font-bold shrink-0">RA</div>
                    <div class="min-w-0">
                        <div class="font-bold text-sm md:text-base text-slate-900 truncate">Rini Andini &middot; <span class="text-slate-500 font-normal">Konsultasi Penurunan Berat Badan</span></div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 mt-1">
                            <i class="ti ti-calendar"></i> Senin, 22 Jun 2026 &middot; 13:00 WIB
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex items-center gap-1 text-[10px] md:text-[11px] font-bold bg-primary-light text-primary-dark px-2.5 py-0.5 rounded-full">
                                <i class="ti ti-circle-check"></i> Dikonfirmasi
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto border-t border-slate-100 pt-3 md:pt-0 md:border-none">
                    <button class="flex-1 md:flex-initial inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary text-white text-xs font-semibold rounded-xl hover:bg-primary-dark transition-colors cursor-pointer border-none shadow-sm"><i class="ti ti-video text-sm"></i> Join</button>
                    <button class="flex-1 md:flex-initial inline-flex items-center justify-center gap-1.5 px-4 py-2 border border-slate-200 bg-white text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">Batalkan</button>
                </div>
            </div>

            <!-- Baris Sesi 2 (Menunggu Konfirmasi) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 shadow-[0_1px_2px_rgb(0_0_0/.03)]">
                <div class="flex items-start md:items-center gap-4 min-w-0">
                    <div class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold shrink-0">DP</div>
                    <div class="min-w-0">
                        <div class="font-bold text-sm md:text-base text-slate-900 truncate">Dimas Pratama &middot; <span class="text-slate-500 font-normal">Evaluasi Program Kekuatan</span></div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400 mt-1">
                            <i class="ti ti-calendar"></i> Rabu, 24 Jun 2026 &middot; 19:00 WIB
                        </div>
                        <div class="mt-2">
                            <span class="inline-flex items-center gap-1 text-[10px] md:text-[11px] font-bold bg-amber-100 text-amber-800 px-2.5 py-0.5 rounded-full">
                                <i class="ti ti-clock"></i> Menunggu Konfirmasi
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center w-full md:w-auto border-t border-slate-100 pt-3 md:pt-0 md:border-none">
                    <button class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 border border-slate-200 bg-white text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-50 transition-colors cursor-pointer">Batalkan</button>
                </div>
            </div>

        </div>
    </div>
    @endauth

</div>
@endsection

@push('scripts')
<script>
    // 1. Pilih Mentor -> Smooth Auto Scroll
    function selectMentor(name) {
        document.getElementById('selectedMentorName').textContent = name;
        const section = document.getElementById('booking-section');
        window.scrollTo({
            top: section.getBoundingClientRect().top + window.scrollY - 40,
            behavior: 'smooth'
        });
    }

    // 2. Toggle Pilihan Tanggal
    document.querySelectorAll('.date-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.date-btn').forEach(b => {
                b.classList.remove('bg-primary', 'border-primary', 'text-white', 'active-date');
                b.classList.add('bg-white', 'border-slate-200', 'text-slate-700');
            });
            this.classList.remove('bg-white', 'border-slate-200', 'text-slate-700');
            this.classList.add('bg-primary', 'border-primary', 'text-white', 'active-date');
        });
    });

    // 3. Toggle Pilihan Jam Slot
    document.querySelectorAll('.booking-slot').forEach(slot => {
        slot.addEventListener('click', function () {
            document.querySelectorAll('.booking-slot').forEach(s => {
                s.classList.remove('bg-primary', 'text-white', 'border-primary', 'active-slot');
                s.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
            });
            this.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
            this.classList.add('bg-primary', 'text-white', 'border-primary', 'active-slot');
        });
    });
</script>
@endpush
