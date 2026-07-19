@extends('admin.layouts.app')

@section('title', $mentor->full_name)
@section('page_title', 'Manajemen Mentor')
@section('page_subtitle', $mentor->full_name)

@section('content')

@php
  $isActive = $mentor->user?->is_active ?? false;
  [$badgeLabel, $badgeClass] = match(true) {
    $mentor->is_verified && $isActive  => ['Aktif', 'bg-primary-light text-primary-dark'],
    ! $mentor->is_verified && $isActive => ['Menunggu Verifikasi', 'bg-amber-100 text-amber-800'],
    default                             => ['Ditolak / Disuspend', 'bg-red-50 text-red-600'],
  };
@endphp

<a href="{{ route('admin.mentors') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 no-underline mb-5">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
  Kembali ke Manajemen Mentor
</a>

<div class="flex items-start justify-between flex-wrap gap-4 mb-6">
  <div class="flex items-center gap-4">
    @if($mentor->profile_photo_url)
      <img src="{{ $mentor->profile_photo_url }}" alt="{{ $mentor->full_name }}"
           class="w-16 h-16 rounded-2xl object-cover flex-shrink-0" />
    @else
      <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-bold text-white flex-shrink-0"
           style="background: linear-gradient(135deg, #1d9e75, #0f6e56);">
        {{ $mentor->initials() }}
      </div>
    @endif
    <div>
      <div class="flex items-center gap-2.5 flex-wrap">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $mentor->full_name }}</h1>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">{{ $badgeLabel }}</span>
      </div>
      <p class="text-sm text-slate-500 mt-1">
        {{ $mentor->certification ?? 'Tanpa sertifikat' }}
        @if($mentor->specialization) · {{ $mentor->specialization }} @endif
        @if($mentor->user?->email) · {{ $mentor->user->email }} @endif
      </p>
    </div>
  </div>

  <div class="flex gap-2">
    @if(! $mentor->is_verified && $isActive)
      <form method="POST" action="{{ route('admin.mentors.verify', $mentor) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="reject" />
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                       bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 border-none cursor-pointer">
          Tolak
        </button>
      </form>
      <form method="POST" action="{{ route('admin.mentors.verify', $mentor) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="approve" />
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                       bg-primary text-white hover:bg-primary-dark transition-all duration-200 border-none cursor-pointer">
          Setujui Mentor
        </button>
      </form>
    @elseif($mentor->is_verified && $isActive)
      <form method="POST" action="{{ route('admin.mentors.toggle-active', $mentor) }}" onsubmit="return confirm('Suspend mentor ini?');">
        @csrf @method('PATCH')
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                       bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 border-none cursor-pointer">
          Suspend Mentor
        </button>
      </form>
    @else
      <form method="POST" action="{{ route('admin.mentors.toggle-active', $mentor) }}">
        @csrf @method('PATCH')
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                       bg-primary text-white hover:bg-primary-dark transition-all duration-200 border-none cursor-pointer">
          Aktifkan Kembali
        </button>
      </form>
    @endif
  </div>
</div>

@if($mentor->bio)
  <div class="bg-white border border-slate-200 rounded-xl p-5 mb-6 text-sm text-slate-600 leading-relaxed">
    {{ $mentor->bio }}
  </div>
@endif

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">{{ $performance['total_programs'] }}</div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Program ({{ $performance['published'] }} terbit)</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">{{ $performance['unique_members'] }}</div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Member Terdaftar</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">
      {{ $performance['avg_progress'] !== null ? number_format($performance['avg_progress'], 1) . '%' : '—' }}
    </div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Rata-rata Progres Member</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">{{ $performance['total_bookings'] }}</div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Booking Konsultasi</div>
  </div>
</div>

{{-- Programs list --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100">
    <div class="text-[14px] font-bold text-slate-900">Program Latihan</div>
    <div class="text-[11px] text-slate-400 mt-0.5">{{ $programs->count() }} program dibuat oleh mentor ini</div>
  </div>

  @if($programs->isEmpty())
    <div class="px-5 py-10 text-center text-[13px] text-slate-400">Mentor ini belum membuat program latihan.</div>
  @else
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-slate-50">
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Program</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Kategori</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Status</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Member</th>
        </tr>
      </thead>
      <tbody>
        @foreach($programs as $program)
          <tr class="border-b border-slate-100 last:border-b-0">
            <td class="px-4 py-3 text-[13px] font-semibold text-slate-900">{{ $program->title }}</td>
            <td class="px-4 py-3 text-[12px] text-slate-500">{{ $program->category }} · {{ $program->levelLabel() }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                           {{ $program->isPublished() ? 'bg-primary-light text-primary-dark' : 'bg-slate-100 text-slate-500' }}">
                {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
              </span>
            </td>
            <td class="px-4 py-3 text-[12px] text-slate-500">{{ $program->enrollments_count }} member</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@endsection
