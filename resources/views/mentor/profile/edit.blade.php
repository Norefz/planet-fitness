@extends('layouts.mentor')
@section('title', 'Profil')

@section('content')

  <div class="mb-8">
    <div class="text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">Pengaturan Akun</div>
    <h1 class="text-[28px] sm:text-3xl font-bold tracking-tight text-slate-900">Profil & Sertifikasi</h1>
    <p class="text-sm text-slate-500 mt-1.5">Informasi ini ditampilkan pada halaman pencarian mentor dan dilihat oleh calon member.</p>
  </div>

  <div class="grid lg:grid-cols-3 gap-6">

    <x-mentor.card class="h-fit">
      <div class="flex flex-col items-center text-center">
        <x-mentor.avatar :name="$mentor->full_name" size="xl" class="mb-4" />
        <h3 class="text-base font-bold text-slate-900">{{ $mentor->full_name }}</h3>
        <p class="text-xs text-slate-500 mt-1">{{ $mentor->specialization ?: 'Belum ada spesialisasi' }}</p>

        <div class="flex items-center gap-1.5 mt-4 text-sm">
          <x-mentor.icon name="star" class="w-4 h-4 text-amber-400" />
          <strong>{{ number_format($mentor->rating, 1) }}</strong>
          <span class="text-slate-400">rating</span>
        </div>

        <div class="mt-4">
          @if ($mentor->is_verified)
            <x-mentor.badge variant="success">
              <x-mentor.icon name="check" class="w-3.5 h-3.5" /> Terverifikasi
            </x-mentor.badge>
          @else
            <x-mentor.badge variant="warning">
              <x-mentor.icon name="clock" class="w-3.5 h-3.5" /> Menunggu Verifikasi Admin
            </x-mentor.badge>
          @endif
        </div>
      </div>
    </x-mentor.card>

    <x-mentor.card class="lg:col-span-2">
      <h3 class="text-sm font-bold text-slate-900 mb-5">Perbarui Informasi</h3>

      <form method="POST" action="{{ route('mentor.profile.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

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

        <div class="flex justify-end pt-2">
          <x-mentor.button type="submit">
            <x-mentor.icon name="check" class="w-4 h-4" /> Simpan Perubahan
          </x-mentor.button>
        </div>
      </form>
    </x-mentor.card>
  </div>

@endsection
