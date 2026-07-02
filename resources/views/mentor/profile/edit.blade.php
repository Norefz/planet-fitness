@extends('layouts.mentor')
@section('title', 'Profil')

@section('content')

  <div class="mb-8">
    <div class="text-xs font-bold text-primary-dark tracking-widest uppercase mb-2">Pengaturan Akun</div>
    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Profil & Sertifikasi</h1>
    <p class="text-sm text-slate-500 mt-1.5">Informasi ini ditampilkan pada halaman pencarian mentor dan dilihat oleh calon member.</p>
  </div>

  <div class="grid lg:grid-cols-3 gap-8">

    {{-- Summary card --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 h-fit">
      <div class="flex flex-col items-center text-center">
        <div class="w-20 h-20 rounded-full bg-primary-light text-primary-dark flex items-center justify-center text-2xl font-bold mb-4">
          {{ $mentor->initials() }}
        </div>
        <h3 class="text-base font-bold">{{ $mentor->full_name }}</h3>
        <p class="text-xs text-slate-500 mt-1">{{ $mentor->specialization ?: 'Belum ada spesialisasi' }}</p>

        <div class="flex items-center gap-1.5 mt-4 text-sm">
          @include('mentor.partials.icon', ['name' => 'star', 'class' => 'w-4 h-4 text-amber-400'])
          <strong>{{ number_format($mentor->rating, 1) }}</strong>
          <span class="text-slate-400">rating</span>
        </div>

        <div class="mt-4">
          @if ($mentor->is_verified)
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-dark bg-primary-light px-3 py-1.5 rounded-full">
              @include('mentor.partials.icon', ['name' => 'check', 'class' => 'w-3.5 h-3.5']) Terverifikasi
            </span>
          @else
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full">
              @include('mentor.partials.icon', ['name' => 'clock', 'class' => 'w-3.5 h-3.5']) Menunggu Verifikasi Admin
            </span>
          @endif
        </div>
      </div>
    </div>

    {{-- Edit form --}}
    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6">
      <h3 class="text-sm font-bold mb-5">Perbarui Informasi</h3>

      <form method="POST" action="{{ route('mentor.profile.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-1.5">
          <label for="name" class="text-xs font-semibold text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255"
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
        </div>

        <div class="flex flex-col gap-1.5">
          <label class="text-xs font-semibold text-slate-700">Email</label>
          <input type="email" value="{{ $user->email }}" disabled
                 class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-400" />
          <p class="text-xs text-slate-400">Email tidak dapat diubah dari halaman ini.</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
          <div class="flex flex-col gap-1.5">
            <label for="specialization" class="text-xs font-semibold text-slate-700">Spesialisasi</label>
            <input type="text" id="specialization" name="specialization" value="{{ old('specialization', $mentor->specialization) }}" maxlength="255"
                   placeholder="mis. Kekuatan & Hipertrofi Otot"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
          </div>
          <div class="flex flex-col gap-1.5">
            <label for="certification" class="text-xs font-semibold text-slate-700">Sertifikasi</label>
            <input type="text" id="certification" name="certification" value="{{ old('certification', $mentor->certification) }}" maxlength="255"
                   placeholder="mis. ACE Certified Personal Trainer"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
          </div>
        </div>

        <div class="flex flex-col gap-1.5">
          <label for="bio" class="text-xs font-semibold text-slate-700">Bio</label>
          <textarea id="bio" name="bio" rows="4" maxlength="1000" placeholder="Ceritakan pengalaman dan keahlianmu..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none">{{ old('bio', $mentor->bio) }}</textarea>
        </div>

        <div class="flex justify-end pt-2">
          <button type="submit"
                  class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white font-semibold text-sm px-5 py-3 rounded-xl shadow-sm hover:-translate-y-px transition-all">
            @include('mentor.partials.icon', ['name' => 'check', 'class' => 'w-4 h-4'])
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

@endsection
