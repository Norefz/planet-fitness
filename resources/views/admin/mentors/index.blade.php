@extends('admin.layouts.app')

@section('title', 'Manajemen Mentor')
@section('page_title', 'Manajemen Mentor')
@section('page_subtitle', 'Verifikasi & Daftar')

@section('content')

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Manajemen Mentor</h1>
    <p class="text-[13px] text-slate-500 mt-0.5">
      {{ $stats['verified'] }} mentor aktif · {{ $stats['pending'] }} menunggu verifikasi · {{ $stats['rejected'] }} ditolak
    </p>
  </div>
</div>

{{-- ══════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-4 gap-4 mb-6">

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#1d9e75;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-primary-light flex items-center justify-center">
        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
      </div>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $stats['verified'] }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Mentor Aktif</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#f59e0b;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-amber-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
      </div>
      @if($stats['pending'] > 0)
        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-800">Pending</span>
      @endif
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $stats['pending'] }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Menunggu Verifikasi</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#3b82f6;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-blue-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>
        </svg>
      </div>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['programs']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Program Dibuat</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#8b5cf6;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-violet-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
      </div>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['avg_rating'], 1) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Rating Rata-rata</div>
    </div>
  </div>
</div>

@if($pendingMentors->isNotEmpty())
{{-- ══════════════════════════════════════════
     PENDING VERIFICATION
══════════════════════════════════════════ --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden mb-6">
  <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
    <div class="flex items-center gap-2.5">
      <div class="w-[30px] h-[30px] rounded-lg bg-amber-50 flex items-center justify-center">
        <svg class="w-[15px] h-[15px] text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
      </div>
      <div>
        <div class="text-[14px] font-bold text-slate-900">Menunggu Verifikasi</div>
        <div class="text-[11px] text-slate-400 mt-0.5">{{ $pendingMentors->count() }} pendaftar baru</div>
      </div>
    </div>
    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
      {{ $pendingMentors->count() }} pending
    </span>
  </div>

  <div class="flex flex-col">
    @foreach($pendingMentors as $mentor)
      <div class="flex items-center gap-3 px-5 py-3.5 border-b border-slate-100 last:border-b-0">
        <div class="w-[38px] h-[38px] rounded-full flex items-center justify-center text-[13px] font-bold text-white flex-shrink-0"
             style="background: linear-gradient(135deg, #1d9e75, #0f6e56);">
          {{ $mentor->initials() }}
        </div>
        <div class="flex-1 min-w-0">
          <a href="{{ route('admin.mentors.show', $mentor) }}" class="text-[13px] font-semibold text-slate-900 truncate hover:underline no-underline">
            {{ $mentor->full_name }}
          </a>
          <div class="text-[11px] text-slate-400 mt-0.5 truncate">
            {{ $mentor->certification ?? 'Tanpa sertifikat' }}
            @if($mentor->specialization) · {{ $mentor->specialization }} @endif
          </div>
        </div>
        <div class="flex gap-1.5 flex-shrink-0">
          <form method="POST" action="{{ route('admin.mentors.verify', $mentor) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="action" value="approve" />
            <button type="submit" class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-bold
                           bg-primary-light text-primary-dark hover:bg-primary-mid transition-all duration-200 cursor-pointer border-none">
              <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              Setuju
            </button>
          </form>
          <form method="POST" action="{{ route('admin.mentors.verify', $mentor) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="action" value="reject" />
            <button type="submit" class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-bold
                           bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 cursor-pointer border-none">
              <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Tolak
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif

{{-- ══════════════════════════════════════════
     FILTER + SEARCH + TABLE
══════════════════════════════════════════ --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
  <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-slate-100 flex-wrap">
    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
      @foreach(['all' => 'Semua', 'verified' => 'Terverifikasi', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'] as $val => $label)
        <a href="{{ route('admin.mentors', array_filter(['status' => $val, 'q' => $q])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150
                  {{ $status === $val ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    <form method="GET" action="{{ route('admin.mentors') }}" class="flex items-center gap-2 bg-slate-100 border border-slate-200 rounded-lg px-3.5 py-2 w-72">
      <input type="hidden" name="status" value="{{ $status }}" />
      <svg class="w-[15px] h-[15px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama, spesialisasi, sertifikasi..."
             class="border-none bg-transparent text-[13px] text-slate-700 outline-none w-full placeholder:text-slate-400" />
    </form>
  </div>

  <table class="w-full border-collapse">
    <thead>
      <tr class="bg-slate-50">
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Mentor</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Spesialisasi</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Rating</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Program</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Status</th>
        <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($mentors as $mentor)
        @php
          $isActive = $mentor->user?->is_active ?? false;
          [$badgeLabel, $badgeClass] = match(true) {
            $mentor->is_verified && $isActive  => ['Aktif', 'bg-primary-light text-primary-dark'],
            ! $mentor->is_verified && $isActive => ['Menunggu', 'bg-amber-100 text-amber-800'],
            default                             => ['Ditolak', 'bg-red-50 text-red-600'],
          };
        @endphp
        <tr class="border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                   style="background: linear-gradient(135deg, #1d9e75, #0f6e56);">
                {{ $mentor->initials() }}
              </div>
              <div class="min-w-0">
                <a href="{{ route('admin.mentors.show', $mentor) }}" class="text-[13px] font-semibold text-slate-900 truncate hover:underline no-underline block">
                  {{ $mentor->full_name }}
                </a>
                <div class="text-[11px] text-slate-400 truncate">{{ $mentor->certification ?? 'Tanpa sertifikat' }}</div>
              </div>
            </div>
          </td>
          <td class="px-4 py-3 text-[12px] text-slate-500">{{ $mentor->specialization ?? '—' }}</td>
          <td class="px-4 py-3 text-[13px] font-bold text-amber-500">★ {{ number_format($mentor->rating, 1) }}</td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-violet-50 text-violet-700">
              {{ $mentor->workout_programs_count }} program
            </span>
          </td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">{{ $badgeLabel }}</span>
          </td>
          <td class="px-4 py-3">
            <div class="flex items-center gap-1.5">
              <a href="{{ route('admin.mentors.show', $mentor) }}" title="Lihat detail"
                 class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all duration-200 no-underline">
                <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>

              @if($mentor->is_verified && $isActive)
                <form method="POST" action="{{ route('admin.mentors.toggle-active', $mentor) }}" onsubmit="return confirm('Suspend mentor ini?');">
                  @csrf @method('PATCH')
                  <button type="submit" title="Suspend"
                          class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-red-500 hover:bg-red-50 transition-all duration-200 cursor-pointer">
                    <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                  </button>
                </form>
              @elseif(! $isActive)
                <form method="POST" action="{{ route('admin.mentors.toggle-active', $mentor) }}">
                  @csrf @method('PATCH')
                  <button type="submit" title="Aktifkan kembali"
                          class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-primary hover:bg-primary-light transition-all duration-200 cursor-pointer">
                    <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                  </button>
                </form>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="px-4 py-10 text-center text-[13px] text-slate-400">
            Tidak ada mentor yang cocok dengan pencarian/filter ini.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($mentors->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
      {{ $mentors->links() }}
    </div>
  @endif
</div>

@endsection
