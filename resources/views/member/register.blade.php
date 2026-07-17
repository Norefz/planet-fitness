@extends('layouts.auth')
@section('title', 'Daftar Member')

@section('content')
<div class="w-full max-w-lg" data-tilt data-tilt-strength="2">

  <div class="bg-white border border-slate-200/70 rounded-3xl shadow-card-3d p-8 sm:p-10 animate-pop-in">

    {{-- Logo (mobile only) --}}
    <a href="{{ url('/') }}" class="flex lg:hidden items-center justify-center gap-2.5 mb-8 no-underline">
      <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center shadow-sm">
        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/>
        </svg>
      </div>
      <span class="text-xl font-bold text-slate-900">Planet Fitness</span>
    </a>

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="display-heading text-3xl font-extrabold mb-2">Buat Akun Member</h1>
      <p class="text-sm text-slate-500">Mulai perjalanan menuju tubuh yang lebih sehat.</p>
    </div>

    {{-- Google Register --}}
    <a href="{{ route('member.auth.google') }}"
       class="flex items-center justify-center gap-3 w-full border border-slate-200 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:-translate-y-px transition-all mb-5">
      <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
      </svg>
      Daftar dengan Google
    </a>

    <div class="flex items-center gap-3 mb-5">
      <div class="flex-1 h-px bg-slate-200"></div>
      <span class="text-xs text-slate-400 font-medium">atau isi form di bawah</span>
      <div class="flex-1 h-px bg-slate-200"></div>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
      <div class="mb-4 flex items-start gap-2.5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700 field-shake">
        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
      </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('member.register') }}" class="space-y-4">
      @csrf

      <div class="flex flex-col gap-1.5">
        <label for="name" class="text-xs font-semibold text-slate-700">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
               placeholder="Misal: Budi Santoso"
               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="email" class="text-xs font-semibold text-slate-700">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
               placeholder="contoh@email.com"
               class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label for="password" class="text-xs font-semibold text-slate-700">Kata Sandi</label>
          <input type="password" id="password" name="password" required autocomplete="new-password"
                 placeholder="Min. 8 karakter"
                 class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="password_confirmation" class="text-xs font-semibold text-slate-700">Konfirmasi Sandi</label>
          <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                 placeholder="Ulangi sandi"
                 class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
      </div>

      <div class="flex items-start gap-2.5 text-sm text-slate-500 pt-1">
        <input type="checkbox" id="terms" name="terms" required class="accent-primary w-4 h-4 mt-0.5 shrink-0 cursor-pointer" />
        <label for="terms" class="cursor-pointer leading-relaxed">
          Saya setuju dengan
          <a href="#" class="text-primary font-semibold hover:underline">Syarat &amp; Ketentuan</a>
          serta
          <a href="#" class="text-primary font-semibold hover:underline">Kebijakan Privasi</a>.
        </label>
      </div>

      <button type="submit" data-magnetic data-magnetic-strength="6"
              class="flex items-center justify-center gap-2 w-full bg-ink-900 hover:bg-black text-white font-semibold text-sm py-3.5 rounded-2xl shadow-elevated transition-colors mt-2">
        Daftar Sekarang
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
      Sudah punya akun?
      <a href="{{ route('member.login') }}" class="text-primary font-semibold hover:text-primary-dark hover:underline transition">Masuk di sini</a>
    </p>
    <p class="mt-2 text-center text-xs text-slate-400">
      Mau daftar sebagai mentor?
      <a href="{{ route('mentor.register') }}" class="text-slate-500 font-semibold hover:underline">Daftar mentor</a>
    </p>

  </div>
</div>
@endsection
