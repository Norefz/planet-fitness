@extends('layouts.auth')
@section('title', 'Daftar Mentor')

@section('content')
<div class="w-full max-w-lg">

  <div class="bg-white border border-slate-200 rounded-2xl shadow-md p-8 sm:p-10">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center justify-center gap-2.5 mb-6 no-underline">
      <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center shadow-sm">
        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/>
        </svg>
      </div>
      <span class="text-xl font-bold text-slate-900">Planet Fitness</span>
    </a>

    {{-- Role badge --}}
    <div class="flex justify-center mb-6">
      <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-3 py-1 rounded-full">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
        Daftar sebagai Mentor
      </span>
    </div>

    <div class="text-center mb-6">
      <h1 class="text-2xl font-bold tracking-tight mb-1.5">Bergabung sebagai Mentor</h1>
      <p class="text-sm text-slate-500">Bagikan keahlianmu kepada ribuan member Planet Fitness.</p>
    </div>

    {{-- Info verifikasi --}}
    <div class="flex items-start gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6 text-sm text-amber-800">
      <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span>Akun mentor memerlukan <strong>verifikasi admin</strong> sebelum bisa digunakan. Proses verifikasi biasanya 1–2 hari kerja.</span>
    </div>

    {{-- Google Register --}}
    <a href="{{ route('mentor.auth.google') }}"
       class="flex items-center justify-center gap-3 w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition mb-5">
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
      <div class="mb-4 flex items-start gap-2.5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div>@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
      </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('mentor.register') }}" class="space-y-4">
      @csrf

      {{-- Data Akun --}}
      <p class="text-xs font-bold text-slate-400 uppercase tracking-wider pt-1">Data Akun</p>

      <div class="flex flex-col gap-1.5">
        <label for="name" class="text-xs font-semibold text-slate-700">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required
               placeholder="Nama sesuai sertifikat"
               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="email" class="text-xs font-semibold text-slate-700">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required
               placeholder="email@kamu.com"
               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label for="password" class="text-xs font-semibold text-slate-700">Kata Sandi</label>
          <input type="password" id="password" name="password" required
                 placeholder="Min. 8 karakter"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="password_confirmation" class="text-xs font-semibold text-slate-700">Konfirmasi Sandi</label>
          <input type="password" id="password_confirmation" name="password_confirmation" required
                 placeholder="Ulangi sandi"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
      </div>

      {{-- Data Mentor --}}
      <p class="text-xs font-bold text-slate-400 uppercase tracking-wider pt-3">Profil Mentor</p>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex flex-col gap-1.5">
          <label for="certification" class="text-xs font-semibold text-slate-700">Sertifikasi <span class="text-slate-400 font-normal">(opsional)</span></label>
          <input type="text" id="certification" name="certification" value="{{ old('certification') }}"
                 placeholder="Misal: ACE, NSCA, ISSA"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
        <div class="flex flex-col gap-1.5">
          <label for="specialization" class="text-xs font-semibold text-slate-700">Spesialisasi <span class="text-slate-400 font-normal">(opsional)</span></label>
          <input type="text" id="specialization" name="specialization" value="{{ old('specialization') }}"
                 placeholder="Misal: Strength, Yoga, Gizi"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="bio" class="text-xs font-semibold text-slate-700">Bio Singkat <span class="text-slate-400 font-normal">(opsional)</span></label>
        <textarea id="bio" name="bio" rows="3" placeholder="Ceritakan pengalaman dan keahlianmu..."
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none">{{ old('bio') }}</textarea>
      </div>

      <div class="flex items-start gap-2.5 text-sm text-slate-500 pt-1">
        <input type="checkbox" id="terms" name="terms" required class="accent-primary w-4 h-4 mt-0.5 shrink-0 cursor-pointer" />
        <label for="terms" class="cursor-pointer leading-relaxed">
          Saya setuju dengan
          <a href="#" class="text-primary font-semibold hover:underline">Syarat &amp; Ketentuan</a>
          dan menyatakan bahwa informasi yang saya berikan adalah benar.
        </label>
      </div>

      <button type="submit"
              class="flex items-center justify-center gap-2 w-full bg-primary hover:bg-primary-dark text-white font-semibold text-sm py-3 rounded-xl shadow-sm hover:-translate-y-px transition-all mt-2">
        Kirim Pendaftaran
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
      Sudah punya akun mentor?
      <a href="{{ route('mentor.login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
    </p>
    <p class="mt-2 text-center text-xs text-slate-400">
      Ingin daftar sebagai member?
      <a href="{{ route('member.register') }}" class="text-slate-500 font-semibold hover:underline">Daftar member</a>
    </p>

  </div>
</div>
@endsection
