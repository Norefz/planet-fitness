@extends('layouts.app')

@section('title', 'Log Nutrisi - Planet Fitness')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>[x-cloak] { display: none !important; }</style>
@endpush

@section('content')
@include('partials.navbar')

<div
    class="min-h-screen bg-slate-50"
    x-data="nutritionPage({
        errors: {{ \Illuminate\Support\Js::from($errors->messages()) }},
        old: {{ \Illuminate\Support\Js::from([
            'food_name' => old('food_name', ''),
            'category'  => old('category', 'breakfast'),
            'calories'  => old('calories', ''),
            'carbs_g'   => old('carbs_g', ''),
            'protein_g' => old('protein_g', ''),
            'fat_g'     => old('fat_g', ''),
        ]) }},
        flashSuccess: {{ \Illuminate\Support\Js::from(session('success')) }},
        flashCelebrate: {{ \Illuminate\Support\Js::from(session('celebrate')) }},
        categoriesToday: {{ \Illuminate\Support\Js::from($logs->pluck('category')->values()) }},
    })"
>
    {{-- ═══════════ Hero gelap ala Apple ═══════════ --}}
    <div class="relative mesh-dark noise-overlay overflow-hidden">
        <div class="absolute -top-16 right-[8%] w-72 h-72 orb animate-orb-float-slow"></div>
        <div class="absolute bottom-[-4rem] left-[6%] w-48 h-48 orb-mini opacity-40 animate-float-y"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-14 reveal-on-scroll">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary-300">Nutrisi Harian</p>
            <h1 class="display-heading mt-2 text-4xl sm:text-5xl font-extrabold text-white">Log Nutrisi</h1>
            <p class="mt-3 max-w-md text-[15px] text-white/60">Catat makananmu, pantau kalori &amp; makronutrisi, dan tetap di jalur target harianmu.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 pb-28 pt-8 sm:px-6 sm:pb-16 lg:px-8 -mt-6">

        {{-- ─── Toasts ──────────────────────────────────────────────────────── --}}
        <div class="pointer-events-none fixed inset-x-0 top-4 z-[70] flex flex-col items-center gap-2 px-4 sm:top-6 sm:items-end sm:px-6" aria-live="polite" aria-atomic="true">
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-show="true"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border border-slate-100 bg-white p-4 shadow-xl"
                >
                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                        :class="toast.type === 'celebrate' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600'"
                    >
                        <i class="ti" :class="toast.type === 'celebrate' ? 'ti-confetti' : 'ti-circle-check'" aria-hidden="true"></i>
                    </div>
                    <p class="flex-1 pt-1 text-sm font-medium text-slate-700" x-text="toast.message"></p>
                    <button @click="dismissToast(toast.id)" class="rounded-full p-1 text-slate-300 transition hover:bg-slate-100 hover:text-slate-500" aria-label="Tutup notifikasi">
                        <i class="ti ti-x text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </template>
        </div>

        {{-- ─── Header: navigasi tanggal ──────────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-end">
            <div class="flex items-center gap-1 self-start rounded-full border border-slate-200 bg-white p-1 shadow-sm sm:self-auto" role="group" aria-label="Navigasi tanggal">
                <a
                    href="{{ route('member.log-nutrisi', ['date' => $prevDate]) }}"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-50 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                    aria-label="Hari sebelumnya"
                >
                    <i class="ti ti-chevron-left" aria-hidden="true"></i>
                </a>
                <span class="min-w-[6.5rem] px-1 text-center text-sm font-bold text-slate-700">
                    {{ $isToday ? 'Hari Ini' : $dateLabel }}
                </span>
                @if ($nextDate)
                    <a
                        href="{{ route('member.log-nutrisi', ['date' => $nextDate]) }}"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-50 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                        aria-label="Hari berikutnya"
                    >
                        <i class="ti ti-chevron-right" aria-hidden="true"></i>
                    </a>
                @else
                    <span class="flex h-9 w-9 items-center justify-center text-slate-200" aria-hidden="true">
                        <i class="ti ti-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>

        {{-- ─── Guest / non-member notice ──────────────────────────────────── --}}
        @unless ($canLog)
            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                <i class="ti ti-info-circle mt-0.5 text-lg text-amber-500" aria-hidden="true"></i>
                <div class="text-sm">
                    @if ($authState === 'guest')
                        <p class="font-semibold text-amber-900">Ini tampilan pratinjau dengan data contoh.</p>
                        <p class="mt-0.5 text-amber-700">
                            <a href="{{ route('member.login') }}" class="font-semibold underline decoration-amber-400 underline-offset-2 hover:text-amber-900">Masuk</a>
                            atau
                            <a href="{{ route('member.register') }}" class="font-semibold underline decoration-amber-400 underline-offset-2 hover:text-amber-900">daftar sebagai Member</a>
                            untuk mulai mencatat makananmu sendiri.
                        </p>
                    @else
                        <p class="font-semibold text-amber-900">Pencatatan nutrisi khusus untuk akun Member.</p>
                        <p class="mt-0.5 text-amber-700">Data di bawah ini hanya contoh tampilan untuk akunmu saat ini.</p>
                    @endif
                </div>
            </div>
        @endunless

        {{-- ─── Main grid ───────────────────────────────────────────────────── --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- LEFT: summary column --}}
            <div class="space-y-6 lg:sticky lg:top-24 lg:col-span-1 lg:self-start">

                {{-- Calorie ring card --}}
                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-card-3d" data-tilt data-tilt-strength="4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-bold uppercase tracking-wide text-slate-400">Kalori {{ $isToday ? 'Hari Ini' : $dateLabel }}</h2>
                        @if ($streak > 0)
                            <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-2.5 py-1 text-xs font-bold text-orange-600" title="Streak mencatat">
                                <i class="ti ti-flame" aria-hidden="true"></i> {{ $streak }} hari
                            </span>
                        @endif
                    </div>

                    <div class="mt-5 flex justify-center">
                        <div class="relative h-48 w-48" x-data="calorieRing({{ (int) round($consumedPct) }}, '{{ $ringStatus }}')">
                            <svg viewBox="0 0 200 200" class="h-full w-full -rotate-90" aria-hidden="true">
                                <circle cx="100" cy="100" r="88" fill="none" stroke="#f1f5f9" stroke-width="16" />
                                <circle
                                    cx="100" cy="100" r="88" fill="none"
                                    :stroke="color"
                                    stroke-width="16"
                                    stroke-linecap="round"
                                    :stroke-dasharray="circumference"
                                    :stroke-dashoffset="offset"
                                    class="transition-all duration-1000 ease-out motion-reduce:transition-none"
                                ></circle>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-extrabold tabular-nums text-slate-900">{{ number_format($totals['calories']) }}</span>
                                <span class="text-xs font-medium text-slate-400">dari {{ number_format($target['calories']) }} kkal</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-5 flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-center
                            {{ match($ringStatus) { 'over' => 'bg-rose-50', 'near' => 'bg-amber-50', default => 'bg-emerald-50' } }}"
                    >
                        <i class="ti {{ match($ringStatus) { 'over' => 'ti-alert-triangle', 'near' => 'ti-alert-circle', default => 'ti-circle-check' } }}
                            {{ match($ringStatus) { 'over' => 'text-rose-500', 'near' => 'text-amber-500', default => 'text-emerald-600' } }}" aria-hidden="true"></i>
                        <p class="text-sm font-semibold {{ match($ringStatus) { 'over' => 'text-rose-600', 'near' => 'text-amber-700', default => 'text-emerald-700' } }}">
                            @if ($remaining >= 0)
                                Sisa {{ number_format($remaining) }} kkal
                            @else
                                {{ number_format(abs($remaining)) }} kkal di atas target
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Macro bars card --}}
                <div class="space-y-5 rounded-3xl border border-slate-100 bg-white p-6 shadow-card-3d">
                    <h2 class="text-xs font-bold uppercase tracking-wide text-slate-400">Makronutrisi</h2>
                    <x-member.macro-bar label="Karbohidrat" :consumed="$totals['carbs_g']" :target="$target['carbs_g']" color="amber" />
                    <x-member.macro-bar label="Protein" :consumed="$totals['protein_g']" :target="$target['protein_g']" color="blue" />
                    <x-member.macro-bar label="Lemak" :consumed="$totals['fat_g']" :target="$target['fat_g']" color="rose" />
                </div>

                {{-- Insight card --}}
                @if ($insight)
                    <div class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50/70 to-white p-5">
                        <div class="flex gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                <i class="ti ti-bulb" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Wawasan Nutrisi</p>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $insight }}</p>
                                @if ($topCategoryLabel)
                                    <p class="mt-2 text-xs text-slate-400">{{ $topCategoryLabel }} menyumbang kalori terbanyak hari ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT: meal list column --}}
            <div class="lg:col-span-2">
                <div class="rounded-3xl border border-slate-100 bg-white shadow-card-3d">

                    {{-- Filter tabs + add button --}}
                    <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-wrap gap-1.5 rounded-full bg-slate-100 p-1" role="tablist" aria-label="Filter kategori makan">
                            <button
                                type="button" role="tab" :aria-selected="activeCategory === 'all'" @click="activeCategory = 'all'"
                                :class="activeCategory === 'all' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="rounded-full px-3.5 py-1.5 text-xs font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                            >Semua</button>
                            @foreach (\App\Models\MealLog::categoryOptions() as $key => $label)
                                <button
                                    type="button" role="tab" :aria-selected="activeCategory === '{{ $key }}'" @click="activeCategory = '{{ $key }}'"
                                    :class="activeCategory === '{{ $key }}' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="rounded-full px-3.5 py-1.5 text-xs font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                                >{{ $label }}</button>
                            @endforeach
                        </div>

                        @if ($canLog)
                            <button
                                type="button"
                                @click="openModal(activeCategory !== 'all' ? { category: activeCategory } : null)"
                                class="inline-flex items-center justify-center gap-2 rounded-xl2 bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2"
                            >
                                <i class="ti ti-plus" aria-hidden="true"></i> Tambah Makanan
                            </button>
                        @else
                            <a
                                href="{{ route('member.register') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl2 border border-dashed border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-400 transition hover:border-emerald-300 hover:text-emerald-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                            >
                                <i class="ti ti-lock" aria-hidden="true"></i> Tambah Makanan
                            </a>
                        @endif
                    </div>

                    {{-- Recent foods quick add --}}
                    @if ($canLog && $recentFoods->isNotEmpty())
                        <div class="border-b border-slate-100 px-5 py-4">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Makanan Terakhir</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($recentFoods as $food)
                                    <button
                                        type="button"
                                        @click="openModal({{ \Illuminate\Support\Js::from([
                                            'food_name' => $food->food_name,
                                            'category'  => $food->category,
                                            'calories'  => $food->calories,
                                            'carbs_g'   => $food->carbs_g,
                                            'protein_g' => $food->protein_g,
                                            'fat_g'     => $food->fat_g,
                                        ]) }})"
                                        class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                                    >
                                        <i class="ti {{ $food->categoryIcon() }} text-sm text-slate-400" aria-hidden="true"></i>
                                        {{ $food->food_name }}
                                        <span class="text-slate-300" aria-hidden="true">&middot;</span>
                                        <span class="text-slate-400">{{ $food->calories }} kkal</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Meal list --}}
                    <div class="p-5">
                        @if ($logs->isEmpty())
                            <x-member.empty-state
                                icon="ti-salad"
                                title="Belum ada makanan tercatat"
                                :description="$canLog ? 'Mulai catat makanan ' . ($isToday ? 'hari ini' : 'untuk tanggal ini') . ' supaya progres nutrisimu terlihat.' : 'Data akan muncul di sini setelah kamu masuk sebagai Member.'"
                            >
                                @if ($canLog)
                                    <x-slot:action>
                                        <button
                                            type="button"
                                            @click="openModal()"
                                            class="inline-flex items-center gap-2 rounded-xl2 bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2"
                                        >
                                            <i class="ti ti-plus" aria-hidden="true"></i> Tambah makanan pertama
                                        </button>
                                    </x-slot:action>
                                @endif
                            </x-member.empty-state>
                        @else
                            <div class="space-y-2.5">
                                @foreach ($logs as $log)
                                    <div
                                        x-show="activeCategory === 'all' || activeCategory === '{{ $log->category }}'"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                    >
                                        <x-member.meal-row :log="$log" :deletable="$canLog" />
                                    </div>
                                @endforeach
                            </div>

                            <div x-show="!hasVisibleMeals" x-cloak class="py-10 text-center text-sm text-slate-400">
                                Tidak ada makanan di kategori ini untuk tanggal yang dipilih.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Mobile FAB ──────────────────────────────────────────────────────── --}}
    @if ($canLog)
        <button
            type="button"
            @click="openModal()"
            class="fixed bottom-6 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-600 text-white shadow-lg shadow-emerald-600/30 transition hover:bg-emerald-700 active:scale-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 sm:hidden"
            aria-label="Tambah makanan"
        >
            <i class="ti ti-plus text-2xl" aria-hidden="true"></i>
        </button>
    @else
        <a
            href="{{ route('member.register') }}"
            class="fixed bottom-6 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-400 shadow-lg ring-1 ring-slate-200 transition hover:text-emerald-600 sm:hidden"
            aria-label="Daftar untuk mulai mencatat makanan"
        >
            <i class="ti ti-lock text-xl" aria-hidden="true"></i>
        </a>
    @endif

    {{-- ─── Add Food Modal ──────────────────────────────────────────────────── --}}
    @if ($canLog)
        <div
            x-show="modalOpen"
            x-cloak
            class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center sm:p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="add-food-title"
            @keydown.escape.window="closeModal()"
        >
            <div
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
                @click="closeModal()"
            ></div>

            <div
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-6 sm:translate-y-0 sm:scale-95"
                class="relative max-h-[92vh] w-full overflow-y-auto rounded-t-3xl bg-white shadow-2xl sm:max-w-lg sm:rounded-3xl"
                @click.outside="closeModal()"
            >
                <form method="POST" action="{{ route('member.log-nutrisi.store') }}" class="flex flex-col">
                    @csrf
                    <input type="hidden" name="log_date" value="{{ $date->toDateString() }}">

                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                        <h2 id="add-food-title" class="text-lg font-bold text-slate-900">Tambah Makanan</h2>
                        <button
                            type="button" @click="closeModal()"
                            class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                            aria-label="Tutup"
                        >
                            <i class="ti ti-x" aria-hidden="true"></i>
                        </button>
                    </div>

                    <div class="space-y-5 px-6 py-5">
                        <div>
                            <label for="food_name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Makanan</label>
                            <input
                                type="text" id="food_name" name="food_name" x-model="form.food_name" x-ref="foodNameInput"
                                value="{{ old('food_name') }}"
                                placeholder="cth. Nasi Goreng Ayam" maxlength="150" required
                                :class="errorFields.food_name ? 'border-rose-300 focus:ring-rose-400' : 'border-slate-200 focus:ring-emerald-400'"
                                class="w-full rounded-xl2 border bg-slate-50 px-4 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:outline-none focus:ring-2"
                            >
                            @error('food_name') <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Kategori</label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach (\App\Models\MealLog::categoryOptions() as $key => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="category" value="{{ $key }}" x-model="form.category" class="peer sr-only" @checked(old('category', 'breakfast') === $key)>
                                        <div class="rounded-xl2 border border-slate-200 px-2 py-2.5 text-center text-xs font-semibold text-slate-500 transition peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 hover:border-slate-300">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('category') <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="calories" class="mb-1.5 block text-sm font-semibold text-slate-700">Kalori (kkal)</label>
                            <input
                                type="number" id="calories" :value="suggestedCalories()" readonly tabindex="-1"
                                class="w-full cursor-not-allowed rounded-xl2 border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm text-slate-500"
                            >
                            <p class="mt-1.5 text-xs text-slate-400">
                                Dihitung otomatis dari karbo, protein &amp; lemak (4 / 4 / 9 kkal per gram) — tidak bisa diubah manual.
                            </p>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="carbs_g" class="mb-1.5 block text-xs font-bold text-amber-600">Karbo (g)</label>
                                <input
                                    type="number" id="carbs_g" name="carbs_g" x-model.number="form.carbs_g" value="{{ old('carbs_g') }}" min="0" max="500" required
                                    class="w-full rounded-xl2 border border-slate-200 bg-amber-50/50 px-3 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-400"
                                >
                                @error('carbs_g') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="protein_g" class="mb-1.5 block text-xs font-bold text-blue-600">Protein (g)</label>
                                <input
                                    type="number" id="protein_g" name="protein_g" x-model.number="form.protein_g" value="{{ old('protein_g') }}" min="0" max="500" required
                                    class="w-full rounded-xl2 border border-slate-200 bg-blue-50/50 px-3 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-400"
                                >
                                @error('protein_g') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="fat_g" class="mb-1.5 block text-xs font-bold text-rose-600">Lemak (g)</label>
                                <input
                                    type="number" id="fat_g" name="fat_g" x-model.number="form.fat_g" value="{{ old('fat_g') }}" min="0" max="500" required
                                    class="w-full rounded-xl2 border border-slate-200 bg-rose-50/50 px-3 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:outline-none focus:ring-2 focus:ring-rose-400"
                                >
                                @error('fat_g') <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                        <button type="button" @click="closeModal()" class="flex-1 rounded-xl2 border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">Batal</button>
                        <button type="submit" class="flex-1 rounded-xl2 bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

@include('partials.footer')
@endsection

@push('scripts')
<script>
    function nutritionPage(config) {
        return {
            activeCategory: 'all',
            modalOpen: false,
            toasts: [],
            errorFields: config.errors || {},
            categoriesToday: config.categoriesToday || [],
            form: {
                food_name: config.old.food_name || '',
                category: config.old.category || 'breakfast',
                calories: config.old.calories || '',
                carbs_g: config.old.carbs_g || '',
                protein_g: config.old.protein_g || '',
                fat_g: config.old.fat_g || '',
            },

            init() {
                if (Object.keys(this.errorFields).length > 0) {
                    this.modalOpen = true;
                }
                if (config.flashSuccess) {
                    this.pushToast(config.flashSuccess, 'success');
                }
                if (config.flashCelebrate) {
                    this.pushToast(config.flashCelebrate, 'celebrate');
                }
            },

            get hasVisibleMeals() {
                return this.activeCategory === 'all'
                    ? this.categoriesToday.length > 0
                    : this.categoriesToday.includes(this.activeCategory);
            },

            openModal(prefill = null) {
                if (prefill) {
                    this.form = { ...this.form, ...prefill };
                }
                this.modalOpen = true;
                this.$nextTick(() => this.$refs.foodNameInput && this.$refs.foodNameInput.focus());
            },

            closeModal() {
                this.modalOpen = false;
            },

            suggestedCalories() {
                const c = Number(this.form.carbs_g) || 0;
                const p = Number(this.form.protein_g) || 0;
                const f = Number(this.form.fat_g) || 0;
                return Math.round((c * 4) + (p * 4) + (f * 9));
            },

            pushToast(message, type = 'success') {
                const id = Date.now() + Math.random();
                this.toasts.push({ id, message, type });
                setTimeout(() => this.dismissToast(id), 5000);
            },

            dismissToast(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            },
        };
    }

    function calorieRing(consumedPct, status) {
        return {
            pct: 0,
            targetPct: Math.max(0, Math.min(consumedPct, 100)),
            circumference: 2 * Math.PI * 88,
            get offset() {
                return this.circumference * (1 - (this.pct / 100));
            },
            get color() {
                if (status === 'over') return '#e11d48';
                if (status === 'near') return '#f59e0b';
                return '#10b981';
            },
            init() {
                this.$nextTick(() => setTimeout(() => { this.pct = this.targetPct; }, 150));
            },
        };
    }
</script>
@endpush
