@extends('layouts.auth')
@section('title', 'Menunggu Persetujuan Admin')

@section('content')
<div class="w-full max-w-lg animate-fade-in-up">

  <div class="spotlight-card bg-white/90 backdrop-blur-xl border border-slate-200/70 rounded-3xl shadow-elevated p-8 sm:p-10">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center justify-center gap-2.5 mb-6 no-underline group">
      <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-[0_1px_0_0_rgba(255,255,255,0.3)_inset,0_6px_16px_-4px_rgba(29,158,117,0.5)] transition-transform duration-200 group-hover:scale-105">
        <x-mentor.icon name="dumbbell" class="w-[18px] h-[18px] text-white" />
      </div>
      <span class="text-xl font-bold text-slate-900 tracking-tight">Planet Fitness</span>
    </a>

    {{-- Status badge --}}
    <div class="flex justify-center mb-6">
      <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold px-3 py-1 rounded-full">
        <x-mentor.icon name="clock" class="w-3.5 h-3.5" />
        Menunggu Persetujuan Admin
      </span>
    </div>

    <div class="text-center mb-7">
      {{-- Clock illustration --}}
      <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-5">
        <x-mentor.icon name="clock" class="w-7 h-7 text-amber-500" />
      </div>

      <h2 class="display-heading text-2xl font-extrabold text-slate-900">Akun Anda Sedang Ditinjau</h2>
      <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">
        Terima kasih sudah melengkapi profil, <strong>{{ $mentor->full_name }}</strong>. Tim admin Planet Fitness
        sedang meninjau data sertifikasi dan spesialisasi Anda. Dashboard mentor akan otomatis terbuka
        begitu akun Anda disetujui.
      </p>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
      <div class="mb-5 p-4 rounded-xl bg-primary-light border border-primary/30 text-primary-dark text-xs font-medium flex items-center gap-2.5">
        <x-mentor.icon name="check-circle" class="w-4 h-4 shrink-0" />
        {{ session('success') }}
      </div>
    @endif

    {{-- Ringkasan profil yang sudah dikirim --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 mb-6 text-xs space-y-2">
      <div class="flex justify-between gap-3">
        <span class="text-slate-400 font-semibold">Sertifikasi</span>
        <span class="text-slate-700 font-medium text-right">{{ $mentor->certification ?? 'Belum diisi' }}</span>
      </div>
      <div class="flex justify-between gap-3">
        <span class="text-slate-400 font-semibold">Spesialisasi</span>
        <span class="text-slate-700 font-medium text-right">{{ $mentor->specialization ?? 'Belum diisi' }}</span>
      </div>
    </div>

    <div class="flex flex-col gap-2.5">
      <x-mentor.button :href="route('mentor.profile.edit')" variant="secondary" size="lg" class="w-full">
        <x-mentor.icon name="edit" class="w-4 h-4" /> Perbarui Profil
      </x-mentor.button>

      <form method="POST" action="{{ route('mentor.logout') }}">
        @csrf
        <x-mentor.button type="submit" variant="ghost" size="lg" class="w-full">
          <x-mentor.icon name="log-out" class="w-4 h-4" /> Keluar
        </x-mentor.button>
      </form>
    </div>

  </div>
</div>
@endsection
