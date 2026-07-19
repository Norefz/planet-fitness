@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview')

@section('content')

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Dashboard</h1>
    <p class="text-[13px] text-slate-500 mt-0.5">
      Selamat datang kembali, <strong>{{ auth('admin')->user()->superAdmin?->full_name ?? auth('admin')->user()->name }}</strong> ·
      {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
    </p>

    </div>
  <div class="flex gap-2">
    <a href="{{ route('admin.reports') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
              bg-white border border-slate-200 text-slate-700 hover:bg-slate-50
              transition-all duration-200 no-underline">
      <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M18 20V10M12 20V4M6 20v-6"/>
      </svg>
      Laporan Lengkap
    </a>
  </div>
</div>

{{-- ══════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-4 gap-4 mb-6">

  {{-- Total Member --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar hover-lift"
       style="--bar-color:#1d9e75;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-primary-light flex items-center justify-center">
        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
      </div>
      <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full
                   bg-primary-light text-primary-dark">
        <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5"
             viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
        +{{ $stats['member_growth'] }}%
      </span>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">
        {{ number_format($stats['total_members']) }}
      </div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Member</div>
      <div class="text-[11px] text-slate-400 mt-0.5">
        {{ number_format($stats['active_members']) }} aktif bulan ini
      </div>
    </div>
  </div>

  {{-- Total Mentor --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar hover-lift"
       style="--bar-color:#3b82f6;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-blue-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="12" cy="8" r="6"/>
          <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
      </div>
      @if($stats['pending_mentors'] > 0)
        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full
                     bg-amber-100 text-amber-800">
          {{ $stats['pending_mentors'] }} pending
        </span>
      @else
        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full
                     bg-primary-light text-primary-dark">
          <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5"
               viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
          +{{ $stats['mentor_growth'] }}%
        </span>
      @endif
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">
        {{ number_format($stats['total_mentors']) }}
      </div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Mentor</div>
      <div class="text-[11px] text-slate-400 mt-0.5">
        {{ $stats['verified_mentors'] }} terverifikasi
      </div>
    </div>
  </div>

  {{-- Program Aktif --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar hover-lift"
       style="--bar-color:#8b5cf6;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-violet-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
      </div>
      <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full
                   bg-primary-light text-primary-dark">
        <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5"
             viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
        +{{ $stats['program_growth'] }}%
      </span>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">
        {{ number_format($stats['total_programs']) }}
      </div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Program Aktif</div>
      <div class="text-[11px] text-slate-400 mt-0.5">
        {{ number_format($stats['total_enrollments']) }} total enrollment
      </div>
    </div>
  </div>

  {{-- Booking Minggu Ini --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5
              shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar hover-lift"
       style="--bar-color:#f59e0b;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-amber-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full
                   bg-green-50 text-green-600">
        Gratis
      </span>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">
        {{ number_format($stats['bookings_this_week']) }}
      </div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Booking Minggu Ini</div>
      <div class="text-[11px] text-slate-400 mt-0.5">
        {{ $stats['pending_bookings'] }} menunggu konfirmasi
      </div>
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════
     ROW 2: Chart + Mini stats
══════════════════════════════════════════ --}}
<div class="grid grid-cols-[2fr_1fr] gap-4 mb-4">

  {{-- Bar chart: Pendaftaran Member --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-primary-light flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-primary" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6" y1="20" x2="6" y2="14"/>
          </svg>
        </div>
        <div>
          <div class="text-[14px] font-bold text-slate-900">Pertumbuhan Member</div>
          <div class="text-[11px] text-slate-400 mt-0.5">Pendaftaran baru per bulan</div>
        </div>
      </div>
      <span class="text-[11px] font-bold px-2 py-1 rounded-full bg-primary-light text-primary-dark">
        +{{ $stats['member_growth'] }}% bulan ini
      </span>
    </div>

    {{-- Bar chart (pure CSS/Tailwind) --}}
    <div class="px-5 pt-5 pb-2 flex items-end gap-2.5 h-[180px]">
      @php
        $maxVal = max(array_column($monthlyData, 'count'));
      @endphp
      @foreach($monthlyData as $month)
        @php
          $heightPct = $maxVal > 0 ? max(8, ($month['count'] / $maxVal) * 100) : 8;
          $isLast = $loop->last;
        @endphp
        <div class="flex-1 flex flex-col items-center gap-2">
          <span class="text-[11px] font-bold text-slate-500">{{ $month['count'] }}</span>
          <div class="chart-bar w-full rounded-t-[4px]"
               style="height:{{ $heightPct }}%;
                      background:{{ $isLast ? '#1d9e75' : '#e2e8f0' }};
                      min-height:6px;"></div>
        </div>
      @endforeach
    </div>
    <div class="flex px-5 pb-4">
      @foreach($monthlyData as $month)
        <div class="flex-1 text-center text-[10px] font-semibold text-slate-400">
          {{ $month['label'] }}
        </div>
      @endforeach
    </div>
  </div>

  {{-- Mini stats column --}}
  <div class="flex flex-col gap-3">

    @php
      $miniStats = [
        ['label' => 'Rata-rata Penyelesaian', 'sub' => 'Dari semua program aktif',
         'val' => $stats['avg_completion'] . '%',
         'color' => 'bg-primary-light', 'icon_color' => 'text-primary',
         'icon' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>'],
        ['label' => 'Rating Rata-rata', 'sub' => 'Dari semua review member',
         'val' => number_format($stats['avg_rating'], 1) . ' / 5.0',
         'color' => 'bg-amber-50', 'icon_color' => 'text-amber-500',
         'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>'],
        ['label' => 'Tingkat Konfirmasi', 'sub' => 'Booking dikonfirmasi mentor',
         'val' => $stats['booking_confirmation_rate'] . '%',
         'color' => 'bg-blue-50', 'icon_color' => 'text-blue-500',
         'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/>'],
      ];
    @endphp

    @foreach($miniStats as $ms)
      <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3
                  shadow-[0_1px_3px_rgb(0_0_0/.08)] flex-1">
        <div class="w-[34px] h-[34px] rounded-[9px] {{ $ms['color'] }} flex items-center justify-center flex-shrink-0">
          <svg class="w-[15px] h-[15px] {{ $ms['icon_color'] }}" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            {!! $ms['icon'] !!}
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-[13px] font-medium text-slate-700">{{ $ms['label'] }}</div>
          <div class="text-[11px] text-slate-400">{{ $ms['sub'] }}</div>
        </div>
        <div class="text-[18px] font-extrabold tracking-tight text-slate-900 flex-shrink-0">
          {{ $ms['val'] }}
        </div>
      </div>
    @endforeach

  </div>
</div>

{{-- ══════════════════════════════════════════
     ROW 3: Member table + Mentor verification
══════════════════════════════════════════ --}}
<div class="grid grid-cols-2 gap-4 mb-4">

  {{-- Recent Members table --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-primary-light flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-primary" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
          </svg>
        </div>
        <div>
          <div class="text-[14px] font-bold text-slate-900">Member Terbaru</div>
          <div class="text-[11px] text-slate-400 mt-0.5">Pendaftar baru minggu ini</div>
        </div>
      </div>
      <a href="{{ route('admin.members') }}"
         class="text-[12px] font-semibold text-primary hover:bg-primary-light px-2.5 py-1.5
                rounded-lg transition-all duration-200 flex items-center gap-1 no-underline">
        Lihat semua
        <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
        </svg>
      </a>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-slate-50">
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-[.5px]
                     border-b border-slate-100">Member</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-[.5px]
                     border-b border-slate-100">Langganan</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-[.5px]
                     border-b border-slate-100">Status</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-[.5px]
                     border-b border-slate-100">Bergabung</th>
          <th class="px-4 py-2.5 border-b border-slate-100"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentMembers as $member)
          <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors duration-150 last:border-b-0">
            <td class="px-4 py-3">
              <div class="flex items-center gap-2.5">
                @if($member->user?->avatar)
                  <img src="{{ $member->user->avatar }}"
                       class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                @else
                  <div class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-bold
                              text-white flex-shrink-0"
                       style="background: linear-gradient(135deg, #{{ substr(md5($member->full_name), 0, 6) }}, #1d9e75);">
                    {{ strtoupper(substr($member->full_name, 0, 2)) }}
                  </div>
                @endif
                <div>
                  <div class="text-[13px] font-semibold text-slate-900">{{ $member->full_name }}</div>
                  <div class="text-[11px] text-slate-400">{{ $member->user?->email ?? '-' }}</div>
                </div>
              </div>
            </td>
            <td class="px-4 py-3">
              @if($member->subscription_type === 'premium')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                             bg-violet-50 text-violet-700">Premium</span>
              @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                             bg-primary-light text-primary-dark">Free</span>
              @endif
            </td>
            <td class="px-4 py-3">
              @if($member->user?->is_active)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                             bg-primary-light text-primary-dark">
                  <svg fill="currentColor" viewBox="0 0 24 24" class="w-2 h-2"><circle cx="12" cy="12" r="5"/></svg>
                  Aktif
                </span>
              @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                             bg-slate-100 text-slate-500">
                  <svg fill="currentColor" viewBox="0 0 24 24" class="w-2 h-2"><circle cx="12" cy="12" r="5"/></svg>
                  Tidak aktif
                </span>
              @endif
            </td>
            <td class="px-4 py-3 text-[12px] text-slate-400">
              {{ $member->created_at->diffForHumans() }}
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-1">
                <a href="{{ route('admin.members.show', $member->id) }}"
                   class="w-7 h-7 rounded-[7px] border border-slate-200 bg-white flex items-center
                          justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-900
                          transition-all duration-200 no-underline">
                  <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2"
                       stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-8 text-center text-[13px] text-slate-400">
              Belum ada member terdaftar.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Mentor Verification --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-amber-50 flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-amber-500" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="12" cy="8" r="6"/>
            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
          </svg>
        </div>
        <div>
          <div class="text-[14px] font-bold text-slate-900">Verifikasi Mentor</div>
          <div class="text-[11px] text-slate-400 mt-0.5">{{ $pendingMentors->count() }} menunggu persetujuan</div>
        </div>
      </div>
      @if($pendingMentors->count() > 0)
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                     bg-amber-100 text-amber-800">
          {{ $pendingMentors->count() }} pending
        </span>
      @endif
    </div>

    <div class="flex flex-col">
      @forelse($pendingMentors as $mentor)
        <div class="flex items-center gap-3 px-5 py-3.5 border-b border-slate-100 last:border-b-0">
          {{-- Avatar --}}
          <div class="w-[38px] h-[38px] rounded-full flex items-center justify-center text-[13px] font-bold
                      text-white flex-shrink-0"
               style="background: linear-gradient(135deg, #1d9e75, #0f6e56);">
            {{ strtoupper(substr($mentor->full_name, 0, 2)) }}
          </div>

          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <div class="text-[13px] font-semibold text-slate-900 truncate">
              {{ $mentor->user?->name ?? $mentor->full_name }}
            </div>
            <div class="text-[11px] text-slate-400 mt-0.5 truncate">
              {{ $mentor->certification ?? 'Belum ada sertifikasi' }}
              @if($mentor->specialization) · {{ $mentor->specialization }} @endif
            </div>
          </div>

          {{-- Actions --}}
          <div class="flex gap-1.5 flex-shrink-0">
            <form method="POST" action="{{ route('admin.mentors.verify', $mentor->id) }}" class="inline">
              @csrf @method('PATCH')
              <input type="hidden" name="action" value="approve" />
              <button type="submit"
                      class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-bold
                             bg-primary-light text-primary-dark hover:bg-primary-mid
                             transition-all duration-200 cursor-pointer border-none">
                <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5"
                     viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Setuju
              </button>
            </form>
            <form method="POST" action="{{ route('admin.mentors.verify', $mentor->id) }}" class="inline">
              @csrf @method('PATCH')
              <input type="hidden" name="action" value="reject" />
              <button type="submit"
                      class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-bold
                             bg-red-50 text-red-600 hover:bg-red-100
                             transition-all duration-200 cursor-pointer border-none">
                <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5"
                     viewBox="0 0 24 24">
                  <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Tolak
              </button>
            </form>
          </div>
        </div>
      @empty
        <div class="px-5 py-8 text-center text-[13px] text-slate-400">
          <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor"
               stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          Tidak ada mentor yang perlu diverifikasi.
        </div>
      @endforelse
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════
     ROW 4: Activity Log
══════════════════════════════════════════ --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
  <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
    <div class="flex items-center gap-2.5">
      <div class="w-[30px] h-[30px] rounded-lg bg-slate-100 flex items-center justify-center">
        <svg class="w-[15px] h-[15px] text-slate-500" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
      </div>
      <div>
        <div class="text-[14px] font-bold text-slate-900">Log Aktivitas Sistem</div>
        <div class="text-[11px] text-slate-400 mt-0.5">Aktivitas terbaru di seluruh platform</div>
      </div>
    </div>
    <a href="{{ route('admin.logs') }}"
       class="text-[12px] font-semibold text-primary hover:bg-primary-light px-2.5 py-1.5
              rounded-lg transition-all duration-200 flex items-center gap-1 no-underline">
      Lihat semua log
      <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2.5"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
      </svg>
    </a>
  </div>

  <div class="flex flex-col">
    @forelse($recentLogs as $log)
      @php
        $dotConfig = match($log->action) {
          'member_register' => ['bg' => 'bg-primary-light', 'icon_color' => 'text-primary',
            'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'badge' => 'Member', 'badge_class' => 'bg-primary-light text-primary-dark'],
          'member_activate' => ['bg' => 'bg-primary-light', 'icon_color' => 'text-primary',
            'icon' => '<polyline points="20 6 9 17 4 12"/>',
            'badge' => 'Diaktifkan', 'badge_class' => 'bg-primary-light text-primary-dark'],
          'member_suspend' => ['bg' => 'bg-red-50', 'icon_color' => 'text-red-500',
            'icon' => '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>',
            'badge' => 'Dinonaktifkan', 'badge_class' => 'bg-red-50 text-red-700'],
          'mentor_verify'   => ['bg' => 'bg-blue-50',        'icon_color' => 'text-blue-500',
            'icon' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
            'badge' => 'Verifikasi', 'badge_class' => 'bg-blue-100 text-blue-700'],
          'program_publish' => ['bg' => 'bg-violet-50',      'icon_color' => 'text-violet-500',
            'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>',
            'badge' => 'Program', 'badge_class' => 'bg-violet-50 text-violet-700'],
          'program_delete'  => ['bg' => 'bg-red-50',         'icon_color' => 'text-red-500',
            'icon' => '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>',
            'badge' => 'Dihapus', 'badge_class' => 'bg-red-50 text-red-700'],
          'booking_confirm' => ['bg' => 'bg-blue-50',        'icon_color' => 'text-blue-500',
            'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
            'badge' => 'Booking', 'badge_class' => 'bg-blue-100 text-blue-700'],
          'booking_cancel'  => ['bg' => 'bg-red-50',         'icon_color' => 'text-red-500',
            'icon' => '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>',
            'badge' => 'Dibatalkan', 'badge_class' => 'bg-red-50 text-red-700'],
          'mentor_reject'   => ['bg' => 'bg-red-50',         'icon_color' => 'text-red-500',
            'icon' => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
            'badge' => 'Ditolak', 'badge_class' => 'bg-red-50 text-red-700'],
          'mentor_suspend'  => ['bg' => 'bg-red-50',         'icon_color' => 'text-red-500',
            'icon' => '<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>',
            'badge' => 'Disuspend', 'badge_class' => 'bg-red-50 text-red-700'],
          'mentor_activate' => ['bg' => 'bg-primary-light',  'icon_color' => 'text-primary',
            'icon' => '<polyline points="20 6 9 17 4 12"/>',
            'badge' => 'Diaktifkan', 'badge_class' => 'bg-primary-light text-primary-dark'],
          default           => ['bg' => 'bg-amber-50',       'icon_color' => 'text-amber-500',
            'icon' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
            'badge' => 'Sistem', 'badge_class' => 'bg-amber-50 text-amber-700'],
        };
      @endphp
      <div class="flex items-center gap-4 px-5 py-3.5 border-b border-slate-100 last:border-b-0">
        <div class="w-[34px] h-[34px] rounded-xl {{ $dotConfig['bg'] }} flex items-center justify-center flex-shrink-0">
          <svg class="w-[15px] h-[15px] {{ $dotConfig['icon_color'] }}" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            {!! $dotConfig['icon'] !!}
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-[13px] text-slate-700 leading-snug">{!! $log->details !!}</p>
          <div class="text-[11px] text-slate-400 mt-0.5">
            {{ $log->created_at->diffForHumans() }}
            @if($log->ip_address) · {{ $log->ip_address }} @endif
          </div>
        </div>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                     flex-shrink-0 {{ $dotConfig['badge_class'] }}">
          {{ $dotConfig['badge'] }}
        </span>
      </div>
    @empty
      <div class="px-5 py-8 text-center text-[13px] text-slate-400">
        Belum ada aktivitas yang tercatat.
      </div>
    @endforelse
  </div>
</div>

@endsection
