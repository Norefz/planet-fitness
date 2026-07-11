@extends('layouts.app')

@section('title', 'Log Nutrisi | Planet Fitness')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

@include('partials.navbar')

<div class="min-h-screen bg-slate-50">

    {{-- ============================
        HEADER
    ============================= --}}
    <section class="bg-gradient-to-r from-emerald-700 via-emerald-600 to-emerald-500 text-white">
        <div class="max-w-7xl mx-auto px-6 py-14">

            <span class="uppercase tracking-[4px] text-sm font-semibold text-emerald-100">
                Healthy Lifestyle
            </span>

            <h1 class="text-4xl md:text-5xl font-bold mt-3">
                Log Nutrisi
            </h1>

            <p class="mt-4 text-emerald-50 max-w-2xl leading-7">
                Pantau konsumsi makanan harianmu untuk menjaga keseimbangan
                kalori, protein, karbohidrat, dan lemak agar target kebugaran
                lebih mudah tercapai.
            </p>

        </div>
    </section>

    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- ============================
            RINGKASAN
            Backend:
            totalCalories
            totalProtein
            totalCarbs
            totalFat
        ============================= --}}

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Total Kalori
                        </p>

                        <h2 class="text-3xl font-bold text-slate-800 mt-2">

                            {{-- Backend --}}
                            {{ $totalCalories ?? 700 }}

                        </h2>

                        <span class="text-xs text-slate-400">
                            kkal hari ini
                        </span>

                    </div>

                    <div class="w-14 h-14 rounded-xl bg-orange-100 flex items-center justify-center">

                        <i class="ti ti-flame text-orange-500 text-3xl"></i>

                    </div>

                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Protein
                        </p>

                        <h2 class="text-3xl font-bold text-slate-800 mt-2">

                            {{ $totalProtein ?? 47 }}

                        </h2>

                        <span class="text-xs text-slate-400">
                            gram
                        </span>

                    </div>

                    <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center">

                        <i class="ti ti-meat text-emerald-600 text-3xl"></i>

                    </div>

                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Karbohidrat
                        </p>

                        <h2 class="text-3xl font-bold text-slate-800 mt-2">

                            {{ $totalCarbs ?? 80 }}

                        </h2>

                        <span class="text-xs text-slate-400">
                            gram
                        </span>

                    </div>

                    <div class="w-14 h-14 rounded-xl bg-yellow-100 flex items-center justify-center">

                        <i class="ti ti-bread text-yellow-600 text-3xl"></i>

                    </div>

                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-slate-500 text-sm">
                            Lemak
                        </p>

                        <h2 class="text-3xl font-bold text-slate-800 mt-2">

                            {{ $totalFat ?? 18 }}

                        </h2>

                        <span class="text-xs text-slate-400">
                            gram
                        </span>

                    </div>

                    <div class="w-14 h-14 rounded-xl bg-pink-100 flex items-center justify-center">

                        <i class="ti ti-droplet text-pink-500 text-3xl"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- ============================
                FORM
                Backend:
                Route::post(nutrition.store)
            ============================= --}}

            <div class="lg:col-span-1">

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200">

                    <div class="px-6 py-5 border-b">

                        <h2 class="text-xl font-bold">
                            Tambah Nutrisi
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">

                            Isi makanan yang baru dikonsumsi.

                        </p>

                    </div>

                    <form
                        action="#"
                        method="POST"
                        class="p-6 space-y-5">

                        @csrf

                        <div>

                            <label class="block mb-2 font-semibold">

                                Nama Makanan

                            </label>

                            <input
                                type="text"
                                name="food_name"
                                value="{{ old('food_name') }}"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                placeholder="Contoh : Ayam Panggang">

                        </div>

                        <div>

                            <label class="block mb-2 font-semibold">

                                Waktu Makan

                            </label>

                            <select
                                name="meal_time"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500">

                                <option>Sarapan</option>

                                <option>Makan Siang</option>

                                <option>Makan Malam</option>

                                <option>Camilan</option>

                            </select>

                        </div>

                        <div class="grid grid-cols-2 gap-4">

                            <div>

                                <label class="block mb-2 font-semibold">

                                    Kalori

                                </label>

                                <input
                                    type="number"
                                    name="calories"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            </div>

                            <div>

                                <label class="block mb-2 font-semibold">

                                    Protein

                                </label>

                                <input
                                    type="number"
                                    name="protein"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                            </div>

                        </div>
                                                <div class="grid grid-cols-2 gap-4">

                            <div>

                                <label class="block mb-2 font-semibold">
                                    Karbohidrat
                                </label>

                                <input
                                    type="number"
                                    name="carbs"
                                    value="{{ old('carbs') }}"
                                    placeholder="0"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                            </div>

                            <div>

                                <label class="block mb-2 font-semibold">
                                    Lemak
                                </label>

                                <input
                                    type="number"
                                    name="fat"
                                    value="{{ old('fat') }}"
                                    placeholder="0"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">

                            </div>

                        </div>

                        <div>

                            <label class="block mb-2 font-semibold">
                                Catatan
                            </label>

                            <textarea
                                name="notes"
                                rows="4"
                                placeholder="Contoh : tanpa gula, extra protein, dll."
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 resize-none focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('notes') }}</textarea>

                        </div>

                        <button
                            type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 duration-300 text-white font-semibold py-3 rounded-xl shadow-lg">

                            <i class="ti ti-plus mr-2"></i>
                            Simpan Log Nutrisi

                        </button>

                    </form>

                </div>

            </div>

            {{-- ====================================================
                 RIWAYAT NUTRISI
                 Backend:
                 kirim $nutritionLogs
            ===================================================== --}}

            <div class="lg:col-span-2">

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                    <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">

                        <div>

                            <h2 class="text-xl font-bold">
                                Riwayat Nutrisi
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Daftar makanan yang telah dikonsumsi hari ini.
                            </p>

                        </div>

                        <div class="bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full text-sm font-semibold">

                            {{ isset($nutritionLogs) ? count($nutritionLogs) : 2 }} Data

                        </div>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-slate-100">

                                <tr class="text-sm text-slate-600">

                                    <th class="text-left px-6 py-4">
                                        Makanan
                                    </th>

                                    <th class="text-left px-6 py-4">
                                        Waktu
                                    </th>

                                    <th class="text-center px-4 py-4">
                                        Kalori
                                    </th>

                                    <th class="text-center px-4 py-4">
                                        Protein
                                    </th>

                                    <th class="text-center px-4 py-4">
                                        Karbo
                                    </th>

                                    <th class="text-center px-4 py-4">
                                        Lemak
                                    </th>

                                    <th class="text-center px-4 py-4">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            {{-- ===============================
                                 Backend

                                 @foreach($nutritionLogs as $log)

                            ================================ --}}

                            @forelse($nutritionLogs ?? [] as $log)

                            <tr class="border-t hover:bg-slate-50 transition">

                                <td class="px-6 py-5 font-semibold">

                                    {{ $log->food_name }}

                                </td>

                                <td class="px-6">

                                    {{ $log->meal_time }}

                                </td>

                                <td class="text-center">

                                    {{ $log->calories }}

                                </td>

                                <td class="text-center">

                                    {{ $log->protein }} g

                                </td>

                                <td class="text-center">

                                    {{ $log->carbs }} g

                                </td>

                                <td class="text-center">

                                    {{ $log->fat }} g

                                </td>

                                <td class="text-center">

                                    <form
                                        action="#"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="w-10 h-10 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 duration-300">

                                            <i class="ti ti-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="7">

                                    <div class="py-20 text-center">

                                        <i class="ti ti-bowl-spoon text-6xl text-slate-300"></i>

                                        <h3 class="mt-5 text-xl font-bold text-slate-700">

                                            Belum Ada Data Nutrisi

                                        </h3>

                                        <p class="mt-2 text-slate-500">

                                            Yuk mulai mencatat makanan pertamamu hari ini.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>