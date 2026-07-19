@extends('layouts.mentor')
@section('title', 'Profil')

@section('content')

  <div class="mb-8">
    <div class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">
      <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span> Pengaturan Akun
    </div>
    <h1 class="display-heading text-[28px] sm:text-3xl font-extrabold text-slate-900">Profil & <span class="text-gradient">Sertifikasi</span></h1>
    <p class="text-sm text-slate-500 mt-1.5">Informasi ini ditampilkan pada halaman pencarian mentor dan dilihat oleh calon member.</p>
  </div>

  <div class="grid lg:grid-cols-3 gap-6">

    <x-mentor.card padding="p-0" class="h-fit overflow-hidden">
      {{-- Gradient banner, Apple-ID-card style --}}
      <div class="relative h-28 bg-gradient-to-br from-ink-900 via-ink-900 to-primary-900 overflow-hidden">
        <div class="absolute -top-6 -right-4 w-28 h-28 orb-mini animate-orb-float opacity-60"></div>
        <div class="absolute inset-0 noise-overlay"></div>
      </div>
      <div class="flex flex-col items-center text-center px-6 pb-6">
        {{-- 1. PERBAIKAN: Menambahkan atribut :image agar foto dari Cloudinary/Storage bisa muncul --}}
        <x-mentor.avatar :name="$mentor->full_name" :image="$mentor->avatar" size="xl" ring class="-mt-10 mb-4" />

        <h3 class="text-base font-bold text-slate-900">{{ $mentor->full_name }}</h3>
        <p class="text-xs text-slate-500 mt-1">{{ $mentor->specialization ?: 'Belum ada spesialisasi' }}</p>

        <div class="flex items-center gap-1.5 mt-4 text-sm">
          <x-mentor.icon name="star" class="w-4 h-4 text-amber-400" />
          <strong>{{ number_format($mentor->rating, 1) }}</strong>
          <span class="text-slate-400">rating</span>
        </div>

        <div class="mt-4">
          @if ($mentor->is_verified)
            <x-mentor.badge variant="success" :dot="true">
              Terverifikasi
            </x-mentor.badge>
          @else
            <x-mentor.badge variant="warning" :dot="true">
              Menunggu Verifikasi Admin
            </x-mentor.badge>
          @endif
        </div>
      </div>
    </x-mentor.card>

    <x-mentor.card class="lg:col-span-2">
      <h3 class="text-sm font-bold text-slate-900 mb-5">Perbarui Informasi</h3>

      {{-- 2. PERBAIKAN: Menambahkan enctype="multipart/form-data" agar form bisa mengirimkan file/foto --}}
      <form method="POST" action="{{ route('mentor.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- 3. PERBAIKAN: Menambahkan input file untuk upload foto profil baru --}}
        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-semibold text-slate-700">Foto Profil (Avatar)</label>
          <input type="file" name="avatar" accept="image/*"
                 class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-sm file:mr-4 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer" />
          <p class="text-xs text-slate-400">Pilih foto terbaikmu. Maksimal ukuran file 2MB (Format: JPG, PNG, WEBP).</p>
        </div>

        <x-mentor.input name="name" label="Nama Lengkap" required maxlength="255" :value="old('name', $user->name)" />

        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-semibold text-slate-700">Email</label>
          <input type="email" value="{{ $user->email }}" disabled
                 class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-400" />
          <p class="text-xs text-slate-400">Email tidak dapat diubah dari halaman ini.</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
          <x-mentor.input name="specialization" label="Spesialisasi" maxlength="255"
                           :value="old('specialization', $mentor->specialization)" placeholder="mis. Kekuatan & Hipertrofi Otot" />
          <x-mentor.input name="certification" label="Sertifikasi" maxlength="255"
                           :value="old('certification', $mentor->certification)" placeholder="mis. ACE Certified Personal Trainer" />
        </div>

        <x-mentor.textarea name="bio" label="Bio" :rows="4" maxlength="1000" placeholder="Ceritakan pengalaman dan keahlianmu...">{{ old('bio', $mentor->bio) }}</x-mentor.textarea>

        <div class="flex items-center justify-between pt-2">
          @unless ($mentor->is_verified)
            <a href="{{ route('mentor.pending-verification') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 hover:text-amber-800 no-underline">
              <x-mentor.icon name="clock" class="w-3.5 h-3.5" /> Lihat Status Verifikasi
            </a>
          @else
            <span></span>
          @endunless

          <x-mentor.button type="submit">
            <x-mentor.icon name="check" class="w-4 h-4" /> Simpan Perubahan
          </x-mentor.button>
        </div>
      </form>
    </x-mentor.card>
  </div>

@endsection
