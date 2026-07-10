@extends('layouts.auth')
@section('title', 'Lengkapi Profil Mentor')

@section('content')
<div class="w-full max-w-lg">

  <div class="bg-white border border-slate-200 rounded-2xl shadow-md p-8 sm:p-10">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center justify-center gap-2.5 mb-6 no-underline">
      <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center shadow-sm">
        <svg class="w-[18px] h-[18px] text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecapRound="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M6 5v14M18 5v14M6 8H2M6 16H2M22 8h-4M22 16h-4M6 12h12"/>
        </svg>
      </div>
      <span class="text-xl font-bold text-slate-900">Planet Fitness</span>
    </a>

    {{-- Role badge --}}
    <div class="flex justify-center mb-6">
      <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-3 py-1 rounded-full">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        </svg>
        LENGKAPI PROFIL MENTOR
      </span>
    </div>

    <div class="text-center mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Satu Langkah Lagi!</h2>
      <p class="text-sm text-slate-500 mt-1.5">Akun Google Anda berhasil terhubung. Silakan isi keahlian fitness Anda untuk melanjutkan ke dashboard.</p>
    </div>

    {{-- Alert Flash Message / Notifikasi Proteksi Middleware --}}
    @if(session('warning'))
      <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium flex items-center gap-2.5">
        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
        </svg>
        {{ session('warning') }}
      </div>
    @endif

    {{-- Info Message dari Google Callback --}}
    @if(session('info'))
      <div class="mb-5 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-medium flex items-center gap-2.5">
        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
        {{ session('info') }}
      </div>
    @endif

    {{-- Error Handling Form --}}
    @if ($errors->any())
      <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
        <ul class="list-disc pl-4 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form Area --}}
    <form method="POST" action="{{ route('mentor.complete-profile.submit') }}" class="space-y-5">
      @csrf

      <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profil Kualifikasi Mentor</p>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Input Sertifikasi --}}
        <div class="flex flex-col gap-1.5">
          <label for="certification" class="text-xs font-semibold text-slate-700">Sertifikasi <span class="text-rose-500">*</span></label>
          <input type="text" id="certification" name="certification" value="{{ old('certification') }}" required
                 placeholder="Misal: ACE, NSCA, ISSA"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>

        {{-- Input Spesialisasi --}}
        <div class="flex flex-col gap-1.5">
          <label for="specialization" class="text-xs font-semibold text-slate-700">Spesialisasi <span class="text-rose-500">*</span></label>
          <input type="text" id="specialization" name="specialization" value="{{ old('specialization') }}" required
                 placeholder="Misal: Strength, Yoga, Gizi"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>
      </div>

      {{-- Input Bio Singkat --}}
      <div class="flex flex-col gap-1.5">
        <label for="bio" class="text-xs font-semibold text-slate-700">Bio Singkat <span class="text-rose-500">*</span></label>
        <textarea id="bio" name="bio" rows="4" required
                  placeholder="Ceritakan pengalaman kepelatihan dan keahlian spesifikmu secara singkat..."
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none">{{ old('bio') }}</textarea>
      </div>

      {{-- Checkbox Persetujuan --}}
      <div class="flex items-start gap-2.5 text-sm text-slate-500 pt-1">
        <input type="checkbox" id="terms" name="terms" required class="accent-primary w-4 h-4 mt-0.5 shrink-0 cursor-pointer" />
        <label for="terms" class="cursor-pointer leading-relaxed text-xs">
          Saya menyatakan data sertifikasi dan profil yang saya masukkan adalah benar dan valid.
        </label>
      </div>

      {{-- Submit Button --}}
      <button type="submit"
              class="flex items-center justify-center gap-2 w-full bg-primary hover:bg-primary-dark text-white font-semibold text-sm py-3 rounded-xl shadow-sm hover:-translate-y-px transition-all mt-2">
        Simpan Profil & Masuk Dashboard
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
        </svg>
      </button>
    </form>

  </div>
</div>
@endsection
