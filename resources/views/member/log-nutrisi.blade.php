@extends('layouts.app')

@section('title', 'Log Nutrisi | Planet Fitness')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

<style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
</style>

@include('partials.navbar')

<div class="min-h-screen bg-slate-50 antialiased py-12">
    <div class="max-w-7xl mx-auto px-6">

        {{-- ============================
            HEADER SECTION
        ============================= --}}
        <div class="mb-10">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">PENCATATAN & PEMANTAUAN</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Log Nutrisi</h1>
            <p class="text-sm text-slate-500 mt-2 max-w-2xl leading-relaxed">
                Catat konsumsi harianmu per kategori makan, lengkap dengan gramasi karbohidrat, protein, dan lemak — dibandingkan otomatis dengan target kalorimu.
            </p>

            {{-- Alert khusus jika yang melihat adalah Guest --}}
            @guest
            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3 max-w-2xl">
                <i class="ti ti-info-circle text-amber-600 text-xl"></i>
                <p class="text-xs text-amber-700">
                    Anda melihat versi **Pratinjau (Guest)**. Silakan <a href="{{ route('login') }}" class="font-bold underline hover:text-amber-900">Login</a> atau <a href="{{ route('register') }}" class="font-bold underline hover:text-amber-900">Daftar</a> untuk mulai mencatat nutrisi Anda sendiri.
                </p>
            </div>
            @endguest
        </div>

        {{-- ============================
            MAIN CONTENT GRID
        ============================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- LEFT COLUMN: CALORIE & MACRO CARD --}}
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm text-center">
                <div class="py-6">
                    <h2 class="text-5xl font-black text-slate-950 tracking-tight">
                        {{-- Member: Angka dinamis dari DB | Guest: Angka statis contoh --}}
                        @auth {{ $totalCalories ?? 0 }} @endauth
                        @guest 1.250 @endguest
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">dari 1.800 kkal</p>
                    <p class="text-xs font-semibold text-slate-700 mt-6">
                        Sisa <span class="text-emerald-600">@auth {{ 1800 - ($totalCalories ?? 0) }} @endauth @guest 550 @endguest kkal</span> untuk hari ini
                    </p>
                </div>

                {{-- Progress Bars --}}
                <div class="space-y-5 mt-6 text-left">
                    {{-- Karbohidrat --}}
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5 text-slate-700">
                            <span>Karbohidrat</span>
                            <span class="text-slate-500">@auth {{ $totalCarbs ?? 0 }}g @endauth @guest 141g @endguest / 220g</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: @auth {{ min((($totalCarbs ?? 0)/220)*100, 100) }}% @endauth @guest 64% @endguest"></div>
                        </div>
                    </div>

                    {{-- Protein --}}
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5 text-slate-700">
                            <span>Protein</span>
                            <span class="text-slate-500">@auth {{ $totalProtein ?? 0 }}g @endauth @guest 87g @endguest / 100g</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full transition-all duration-500" style="width: @auth {{ min((($totalProtein ?? 0)/100)*100, 100) }}% @endauth @guest 87% @endguest"></div>
                        </div>
                    </div>

                    {{-- Lemak --}}
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1.5 text-slate-700">
                            <span>Lemak</span>
                            <span class="text-slate-500">@auth {{ $totalFat ?? 0 }}g @endauth @guest 28g @endguest / 60g</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: @auth {{ min((($totalFat ?? 0)/60)*100, 100) }}% @endauth @guest 46% @endguest"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: LOG HARI INI --}}
            <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-900">Log Hari Ini</h2>
                    <span class="text-xs text-slate-400 font-medium">Jum, 20 Jun 2026</span>
                </div>

                {{-- Filter Kategori Tabs --}}
                <div class="flex flex-wrap gap-2 mb-8">
                    <button class="px-4 py-1.5 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">Semua</button>
                    <button class="px-4 py-1.5 bg-slate-100 text-slate-600 text-xs font-medium rounded-full hover:bg-slate-200 transition">Sarapan</button>
                    <button class="px-4 py-1.5 bg-slate-100 text-slate-600 text-xs font-medium rounded-full hover:bg-slate-200 transition">Makan Siang</button>
                    <button class="px-4 py-1.5 bg-slate-100 text-slate-600 text-xs font-medium rounded-full hover:bg-slate-200 transition">Makan Malam</button>
                    <button class="px-4 py-1.5 bg-slate-100 text-slate-600 text-xs font-medium rounded-full hover:bg-slate-200 transition">Snack</button>
                </div>

                {{-- LIST MAKANAN --}}
                <div class="space-y-6 mb-8">
                    @auth
                        {{-- JIKA MEMBER LOGGED IN: Tampilkan data dari database --}}
                        @forelse($nutritionLogs ?? [] as $log)
                        <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-2xl transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                                    <i class="ti ti-tools-kitchen-2"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $log->food_name }}</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $log->meal_time }}</p>
                                    <div class="flex gap-1.5 mt-2">
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded">Karbo {{ $log->carbs }}g</span>
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded">Protein {{ $log->protein }}g</span>
                                        <span class="px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-bold rounded">Lemak {{ $log->fat }}g</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-bold text-emerald-600 text-sm">{{ $log->calories }} kkal</span>
                        </div>
                        @empty
                        <div class="text-center py-12 text-slate-400 text-xs">
                            <i class="ti ti-tools-kitchen text-4xl mb-2 block"></i>
                            Belum ada makanan dicatat hari ini.
                        </div>
                        @endforelse
                    @endauth

                    @guest
                        {{-- JIKA GUEST: Tampilkan Data Mockup Persis Seperti di Figma --}}
                        <div class="flex items-center justify-between p-1">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg">🥣</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Oatmeal + Buah Pisang</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Sarapan</p>
                                    <div class="flex gap-1.5 mt-1.5">
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-bold rounded">Karbo 48g</span>
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded">Protein 8g</span>
                                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-bold rounded">Lemak 4g</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-bold text-emerald-600 text-sm">310 kkal</span>
                        </div>

                        <div class="flex items-center justify-between p-1">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg">🍲</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Nasi + Ayam Bakar</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Makan Siang</p>
                                    <div class="flex gap-1.5 mt-1.5">
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-bold rounded">Karbo 65g</span>
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded">Protein 32g</span>
                                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-bold rounded">Lemak 12g</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-bold text-emerald-600 text-sm">485 kkal</span>
                        </div>

                        <div class="flex items-center justify-between p-1">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg">🥗</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Salad Tuna + Roti Gandum</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Makan Malam</p>
                                    <div class="flex gap-1.5 mt-1.5">
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-bold rounded">Karbo 28g</span>
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded">Protein 22g</span>
                                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-bold rounded">Lemak 9g</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-bold text-emerald-600 text-sm">290 kkal</span>
                        </div>

                        <div class="flex items-center justify-between p-1">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg">🥤</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">Protein Shake</h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Snack</p>
                                    <div class="flex gap-1.5 mt-1.5">
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 text-[10px] font-bold rounded">Karbo 10g</span>
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded">Protein 25g</span>
                                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-bold rounded">Lemak 3g</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-bold text-emerald-600 text-sm">165 kkal</span>
                        </div>
                    @endguest
                </div>

                {{-- ACTION BUTTON --}}
                @auth
                    {{-- Aktifkan Modal / Form tambah makanan milik member --}}
                    <button onclick="alert('Buka Form Tambah Makanan')" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-2xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="ti ti-plus text-base"></i> Tambah Makanan
                    </button>
                @endauth

                @guest
                    {{-- Dikunci jika dia Guest --}}
                    <a href="{{ route('login') }}" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-2xl text-center block no-underline transition shadow-sm">
                        🔒 Login untuk Tambah Makanan
                    </a>
                @endguest

            </div>
        </div>
    </div>
</div>
@endsection
