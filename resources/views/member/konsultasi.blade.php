<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi Mentor - Planet Fitness</title>

    <!-- Tailwind & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        'primary-dark': '#047857'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js untuk Dropdown Navbar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex flex-col justify-between">

    <div>
        {{-- 1. Panggil Partial Navbar --}}
        @include('partials.navbar')

        {{-- 2. Container Konten Utama --}}
        <main class="max-w-7xl mx-auto px-[5%] py-10 space-y-10">

            {{-- Alert Berhasil / Gagal dari Controller --}}
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-xl text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error') || $errors->any())
                <div class="bg-rose-100 border border-rose-400 text-rose-800 px-4 py-3 rounded-xl text-sm font-bold">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <!-- Header Section -->
            <div class="bg-[#0f2d24] border border-emerald-950 p-8 rounded-2xl shadow-sm text-white">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Penjadwalan & Interaksi</span>
                <h2 class="text-3xl font-black mt-1">Konsultasi Video Live</h2>
                <p class="text-emerald-200/70 text-sm mt-2 max-w-2xl">
                    Booking sesi video konsultasi langsung dengan mentor bersertifikat kami — otomatis dibuatkan link Zoom setelah disetujui.
                </p>
            </div>

            <!-- 1. Pilih Mentor (Looping Dinamis dari DB atau fallback preview) -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-sm font-black">1</span>
                    Pilih Mentor
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(isset($mentors) && $mentors->count() > 0)
                        @foreach($mentors as $mentor)
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:border-emerald-500/50 hover:shadow-xl transition duration-300">
                                <div>
                                    <div class="flex items-center space-x-4 mb-4">
                                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-lg">
                                            {{ strtoupper(substr($mentor->user->name ?? 'M', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">{{ $mentor->user->name ?? 'Mentor' }}</h4>
                                            <p class="text-xs text-slate-500">{{ $mentor->specialization ?? 'Pelatih Kebugaran' }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 mb-3">
                                        <i class="fa-solid fa-certificate mr-1"></i> Bersertifikat
                                    </span>
                                </div>

                                @guest
                                    <a href="{{ route('login') }}" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider text-center transition block">
                                        Login untuk Booking
                                    </a>
                                @else
                                    <button type="button" onclick="selectMentor('{{ $mentor->id }}', '{{ $mentor->user->name }}')" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition">
                                        Booking {{ explode(' ', $mentor->user->name)[0] }}
                                    </button>
                                @endguest
                            </div>
                        @endforeach
                    @else
                        <!-- Tampilan Fallback jika diakses lewat rute preview tanpa data DB -->
                        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-lg">RA</div>
                                <div><h4 class="font-bold text-slate-900">Rini Andini</h4><p class="text-xs text-slate-500">Pelatih Kebugaran</p></div>
                            </div>
                            <a href="{{ route('login') }}" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-xs text-center block">Login untuk Booking</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Atur Jadwal Konsultasi -->
            <div id="booking-section" class="bg-white rounded-2xl border border-slate-200 p-8 space-y-6 shadow-sm relative overflow-hidden">

                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 text-sm font-black">2</span>
                    Atur Jadwal Konsultasi — <span id="selectedMentorName" class="text-emerald-600">Pilih mentor di atas</span>
                </h3>

                <form action="{{ Auth::check() ? route('member.konsultasi.store') : '#' }}" method="POST" class="space-y-6">
                    @csrf
                    <!-- ID Mentor asli dikirim lewat input ini -->
                    <input type="hidden" name="mentor_id" id="mentorIdInput" value="">

                    <!-- Form Topik Konsultasi -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Topik Konsultasi</label>
                        <input type="text" name="topic" placeholder="Misal: Konsultasi Penurunan Berat Badan" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-emerald-500" {{ Auth::guest() ? 'disabled' : '' }}>
                    </div>

                    <!-- Input Gabungan Tanggal & Waktu (Sesuai validasi Laravel: scheduled_at) -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pilih Waktu & Tanggal Sesi</label>
                        <input type="datetime-local" name="scheduled_at" required class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-emerald-500" {{ Auth::guest() ? 'disabled' : '' }}>
                        <p class="text-[11px] text-slate-400 mt-1">* Waktu konsultasi menggunakan zona waktu WIB.</p>
                    </div>

                    <!-- Button Kirim Dinamis -->
                    @guest
                        <a href="{{ route('login') }}" class="w-full py-4 bg-slate-800 hover:bg-slate-950 text-white font-black rounded-xl text-sm transition tracking-wider uppercase flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock"></i> Login untuk Membuat Jadwal
                        </a>
                    @else
                        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-xl text-sm transition tracking-wider uppercase flex items-center justify-center gap-2 shadow-md shadow-emerald-700/10">
                            <i class="fa-solid fa-paper-plane"></i> Ajukan Permintaan Booking
                        </button>
                    @endguest
                </form>

                @guest
                    <div class="absolute inset-0 bg-slate-50/10 backdrop-blur-[1px] pointer-events-none select-none"></div>
                @endguest
            </div>
        </main>
    </div>

    {{-- 3. Panggil Partial Footer --}}
    @include('partials.footer')

    <script>
        function selectMentor(id, name) {
            document.getElementById('selectedMentorName').textContent = name;
            document.getElementById('mentorIdInput').value = id;

            document.getElementById('booking-section').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    </script>
</body>
</html>
