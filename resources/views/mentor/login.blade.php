@extends('layouts.auth')
@section('title', 'Masuk Mentor')

@section('content')
<div class="w-full max-w-md animate-fade-in-up">

  <div class="spotlight-card bg-white/90 backdrop-blur-xl border border-slate-200/70 rounded-3xl shadow-elevated p-8 sm:p-10">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center justify-center gap-2.5 mb-6 no-underline group">
      <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-[0_1px_0_0_rgba(255,255,255,0.3)_inset,0_6px_16px_-4px_rgba(29,158,117,0.5)] transition-transform duration-200 group-hover:scale-105">
        <x-mentor.icon name="dumbbell" class="w-[18px] h-[18px] text-white" />
      </div>
      <span class="text-xl font-bold text-slate-900 tracking-tight">Planet Fitness</span>
    </a>

    {{-- Role badge --}}
    <div class="flex justify-center mb-6">
      <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-3 py-1 rounded-full">
        <x-mentor.icon name="star" class="w-3.5 h-3.5" />
        Portal Mentor
      </span>
    </div>

    <div class="text-center mb-8">
      <h1 class="display-heading text-2xl font-extrabold mb-1.5">Masuk sebagai Mentor</h1>
      <p class="text-sm text-slate-500">Kelola program latihan dan jadwal konsultasimu.</p>
    </div>

    {{-- Flash success (setelah register) --}}
    @if (session('success'))
      <div class="mb-4 flex items-start gap-2.5 bg-primary-light border border-primary text-primary-dark rounded-xl px-4 py-3 text-sm font-medium">
        <x-mentor.icon name="check-circle" class="w-4 h-4 mt-0.5 shrink-0" />
        {{ session('success') }}
      </div>
    @endif

    {{-- Google Login --}}
    <a href="{{ route('mentor.auth.google') }}"
       class="flex items-center justify-center gap-3 w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 hover:-translate-y-0.5 hover:shadow-sm transition-all duration-200 mb-5">
      <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
      </svg>
      Masuk dengan Google
    </a>

    <div class="flex items-center gap-3 mb-5">
      <div class="flex-1 h-px bg-slate-200"></div>
      <span class="text-xs text-slate-400 font-medium">atau masuk dengan email</span>
      <div class="flex-1 h-px bg-slate-200"></div>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
      <div class="mb-4 flex items-start gap-2.5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        <x-mentor.icon name="alert-circle" class="w-4 h-4 mt-0.5 shrink-0" />
        <div>@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
      </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('mentor.login') }}" class="space-y-4">
      @csrf

      <x-mentor.input name="email" label="Alamat Email" type="email" :value="old('email')" required autocomplete="email" placeholder="mentor@email.com" icon="user" />
      <x-mentor.input name="password" label="Kata Sandi" type="password" required autocomplete="current-password" placeholder="••••••••" />

      <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2 cursor-pointer text-slate-500 font-medium">
          <input type="checkbox" name="remember" class="accent-primary-500 w-4 h-4 cursor-pointer rounded" />
          Ingat saya
        </label>
        <a href="#" class="text-primary-600 font-semibold hover:text-primary-700 hover:underline transition">Lupa sandi?</a>
      </div>

      <x-mentor.button type="submit" size="lg" magnetic class="w-full mt-2">
        Masuk sebagai Mentor
        <x-mentor.icon name="chevron-right" class="w-4 h-4" />
      </x-mentor.button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
      Belum terdaftar sebagai mentor?
      <a href="{{ route('mentor.register') }}" class="text-primary-600 font-semibold hover:text-primary-700 hover:underline transition">Daftar di sini</a>
    </p>
    <p class="mt-2 text-center text-xs text-slate-400">
      Kamu member biasa?
      <a href="{{ route('member.login') }}" class="text-slate-500 font-semibold hover:underline">Masuk sebagai member</a>
    </p>

  </div>
</div>
@endsection
