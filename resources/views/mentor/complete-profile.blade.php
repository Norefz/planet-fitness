@extends('layouts.auth')
@section('title', 'Lengkapi Profil Mentor')

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

    {{-- Role badge --}}
    <div class="flex justify-center mb-6">
      <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold px-3 py-1 rounded-full">
        <x-mentor.icon name="star" class="w-3.5 h-3.5" />
        Lengkapi Profil Mentor
      </span>
    </div>

    <div class="text-center mb-7">
      <h2 class="display-heading text-2xl font-extrabold text-slate-900">Satu Langkah Lagi!</h2>
      <p class="text-sm text-slate-500 mt-1.5">Akun Google Anda berhasil terhubung. Silakan isi keahlian fitness Anda untuk melanjutkan ke dashboard.</p>
    </div>

    {{-- Alert Flash Message / Notifikasi Proteksi Middleware --}}
    @if(session('warning'))
      <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium flex items-center gap-2.5">
        <x-mentor.icon name="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0" />
        {{ session('warning') }}
      </div>
    @endif

    {{-- Info Message dari Google Callback --}}
    @if(session('info'))
      <div class="mb-5 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-medium flex items-center gap-2.5">
        <x-mentor.icon name="alert-circle" class="w-4 h-4 text-blue-600 shrink-0" />
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
        <x-mentor.input name="certification" label="Sertifikasi" required :value="old('certification')" placeholder="Misal: ACE, NSCA, ISSA" />
        <x-mentor.input name="specialization" label="Spesialisasi" required :value="old('specialization')" placeholder="Misal: Strength, Yoga, Gizi" />
      </div>

      <x-mentor.textarea name="bio" label="Bio Singkat" required :rows="4" placeholder="Ceritakan pengalaman kepelatihan dan keahlian spesifikmu secara singkat...">{{ old('bio') }}</x-mentor.textarea>

      {{-- Checkbox Persetujuan --}}
      <div class="flex items-start gap-2.5 text-sm text-slate-500 pt-1">
        <input type="checkbox" id="terms" name="terms" required class="accent-primary-500 w-4 h-4 mt-0.5 shrink-0 cursor-pointer rounded" />
        <label for="terms" class="cursor-pointer leading-relaxed text-xs">
          Saya menyatakan data sertifikasi dan profil yang saya masukkan adalah benar dan valid.
        </label>
      </div>

      <x-mentor.button type="submit" size="lg" magnetic class="w-full mt-2">
        Simpan Profil & Masuk Dashboard
        <x-mentor.icon name="chevron-right" class="w-4 h-4" />
      </x-mentor.button>
    </form>

  </div>
</div>
@endsection
