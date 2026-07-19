@extends('admin.layouts.app')

@section('title', 'Laporan & Analitik')
@section('page_title', 'Laporan & Analitik')
@section('page_subtitle', 'Ringkasan Performa')

@section('content')

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between flex-wrap gap-3 mb-6">
  <div>
    <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Laporan & Analitik</h1>
    <p class="text-[13px] text-slate-500 mt-0.5">Ringkasan performa platform · diperbarui setiap hari pukul 00.00</p>
  </div>

  <div class="flex items-center gap-2">
    <div class="flex items-center gap-1 bg-slate-100 rounded-xl p-1">
      @foreach([7 => '7 Hari', 30 => '30 Hari', 90 => '90 Hari'] as $val => $label)
        <a href="{{ route('admin.reports', ['period' => $val]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 no-underline
                  {{ $period === $val ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>
    <button type="button" onclick="window.print()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                   bg-white border border-slate-200 text-slate-700 hover:bg-slate-50
                   transition-all duration-200 cursor-pointer">
      <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
      Export PDF
    </button>
  </div>
</div>

{{-- ══════════════════════════════════════════
     KPI CARDS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-4 gap-4 mb-6">

  {{-- Total Member --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#1d9e75;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-primary-light flex items-center justify-center">
        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
      </div>
      <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full {{ $kpi['member_growth'] >= 0 ? 'bg-primary-light text-primary-dark' : 'bg-red-50 text-red-600' }}">
        <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          @if($kpi['member_growth'] >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
        </svg>
        {{ $kpi['member_growth'] >= 0 ? '+' : '' }}{{ $kpi['member_growth'] }}%
      </span>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($kpi['total_members']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Member</div>
      <div class="text-[11px] text-slate-400 mt-0.5">vs {{ number_format($kpi['prev_members']) }} {{ $periodLabel }} lalu</div>
    </div>
  </div>

  {{-- Total Enrollment --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#3b82f6;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-blue-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>
        </svg>
      </div>
      <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full {{ $kpi['enrollment_growth'] >= 0 ? 'bg-primary-light text-primary-dark' : 'bg-red-50 text-red-600' }}">
        <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          @if($kpi['enrollment_growth'] >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
        </svg>
        {{ $kpi['enrollment_growth'] >= 0 ? '+' : '' }}{{ $kpi['enrollment_growth'] }}%
      </span>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($kpi['total_enrollments']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Enrollment</div>
      <div class="text-[11px] text-slate-400 mt-0.5">vs {{ number_format($kpi['prev_enrollments']) }} {{ $periodLabel }} lalu</div>
    </div>
  </div>

  {{-- Booking Konsultasi --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#8b5cf6;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-violet-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
      </div>
      <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full {{ $kpi['booking_growth'] >= 0 ? 'bg-primary-light text-primary-dark' : 'bg-red-50 text-red-600' }}">
        <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          @if($kpi['booking_growth'] >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
        </svg>
        {{ $kpi['booking_growth'] >= 0 ? '+' : '' }}{{ $kpi['booking_growth'] }}%
      </span>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($kpi['total_bookings']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Booking Konsultasi</div>
      <div class="text-[11px] text-slate-400 mt-0.5">vs {{ number_format($kpi['prev_bookings']) }} {{ $periodLabel }} lalu</div>
    </div>
  </div>

  {{-- Rata-rata Penyelesaian --}}
  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#f59e0b;">
    <div class="flex items-start justify-between">
      <div class="w-10 h-10 rounded-[10px] bg-amber-50 flex items-center justify-center">
        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
      </div>
      <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-full {{ $kpi['completion_diff'] >= 0 ? 'bg-primary-light text-primary-dark' : 'bg-red-50 text-red-600' }}">
        <svg class="w-[11px] h-[11px]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          @if($kpi['completion_diff'] >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
        </svg>
        {{ $kpi['completion_diff'] >= 0 ? '+' : '' }}{{ $kpi['completion_diff'] }}pt
      </span>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $kpi['avg_completion'] }}%</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Rata-rata Penyelesaian</div>
      <div class="text-[11px] text-slate-400 mt-0.5">vs {{ $kpi['avg_completion_prev'] }}% {{ $periodLabel }} lalu</div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     GROWTH CHART + SUBSCRIPTION DONUT
══════════════════════════════════════════ --}}
<div class="grid grid-cols-[1.6fr_1fr] gap-4 mb-4">

  {{-- Pertumbuhan Member --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-primary-light flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
        </div>
        <div>
          <div class="text-[14px] font-bold text-slate-900">Pertumbuhan Member</div>
          <div class="text-[11px] text-slate-400 mt-0.5">12 bulan terakhir</div>
        </div>
      </div>
    </div>

    @php $maxMember = max(array_column($memberGrowthChart, 'count')) ?: 1; @endphp
    <div class="px-5 pt-5 pb-2 flex items-end gap-2.5 h-[200px]">
      @foreach($memberGrowthChart as $m)
        @php
          $heightPct = $maxMember > 0 ? max(4, ($m['count'] / $maxMember) * 100) : 4;
          $isLast = $loop->last;
        @endphp
        <div class="flex-1 flex flex-col items-center gap-2" title="{{ $m['label'] }}: {{ $m['count'] }} member baru">
          <div class="chart-bar w-full rounded-t-[4px]"
               style="height:{{ $heightPct }}%; min-height:4px;
                      background:{{ $isLast ? 'linear-gradient(180deg,#1d9e75,#0f6e56)' : '#e2e8f0' }};"></div>
        </div>
      @endforeach
    </div>
    <div class="flex px-5 pb-4">
      @foreach($memberGrowthChart as $m)
        <div class="flex-1 text-center text-[10px] font-semibold text-slate-400">{{ $m['label'] }}</div>
      @endforeach
    </div>
  </div>

  {{-- Distribusi Langganan --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-violet-50 flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-violet-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/>
          </svg>
        </div>
        <div>
          <div class="text-[14px] font-bold text-slate-900">Distribusi Langganan</div>
          <div class="text-[11px] text-slate-400 mt-0.5">Total {{ number_format($kpi['total_members']) }} member</div>
        </div>
      </div>
    </div>
    <div class="p-5">
      @php
        $circumference = 251.2;
        $offset = $circumference * (1 - ($subscription['premium_pct'] / 100));
      @endphp
      <svg viewBox="0 0 100 100" class="w-[140px] h-[140px] block mx-auto mb-5">
        <circle cx="50" cy="50" r="40" fill="none" stroke="#f1f5f9" stroke-width="14"/>
        <circle cx="50" cy="50" r="40" fill="none" stroke="#1d9e75" stroke-width="14"
                stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}"
                stroke-linecap="round" transform="rotate(-90 50 50)"/>
        <text x="50" y="46" text-anchor="middle" font-size="18" font-weight="800" fill="#0f172a" font-family="Plus Jakarta Sans, sans-serif">{{ $subscription['premium_pct'] }}%</text>
        <text x="50" y="60" text-anchor="middle" font-size="7" fill="#94a3b8" font-family="Plus Jakarta Sans, sans-serif">Premium</text>
      </svg>
      <div class="flex flex-col gap-2.5">
        <div class="flex items-center gap-2.5 text-[13px]">
          <div class="w-2.5 h-2.5 rounded-[3px] flex-shrink-0" style="background:#1d9e75;"></div>
          <div class="flex-1 text-slate-600">Premium</div>
          <div class="font-bold text-slate-900">{{ number_format($subscription['premium']) }}</div>
        </div>
        <div class="flex items-center gap-2.5 text-[13px]">
          <div class="w-2.5 h-2.5 rounded-[3px] flex-shrink-0" style="background:#e2e8f0;"></div>
          <div class="flex-1 text-slate-600">Free</div>
          <div class="font-bold text-slate-900">{{ number_format($subscription['free']) }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     LEADERBOARDS + BOOKING STATUS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-3 gap-4 mb-4">

  {{-- Mentor Terbaik --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-amber-50 flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
          </svg>
        </div>
        <div class="text-[14px] font-bold text-slate-900">Mentor Terbaik</div>
      </div>
    </div>
    <div class="flex flex-col">
      @forelse($topMentors as $mentor)
        <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 last:border-b-0">
          <div class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold flex-shrink-0
                      {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
            {{ $loop->iteration }}
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-[13px] font-semibold text-slate-900 truncate">{{ $mentor->full_name }}</div>
            <div class="text-[11px] text-slate-400">{{ $mentor->workout_programs_count }} program · ★ {{ number_format($mentor->rating, 1) }}</div>
          </div>
          <div class="text-[13px] font-bold text-primary flex-shrink-0">{{ number_format($mentor->total_members) }}</div>
        </div>
      @empty
        <div class="px-4 py-8 text-center text-[13px] text-slate-400">Belum ada data mentor.</div>
      @endforelse
    </div>
  </div>

  {{-- Program Terpopuler --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-primary-light flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
          </svg>
        </div>
        <div class="text-[14px] font-bold text-slate-900">Program Terpopuler</div>
      </div>
    </div>
    <div class="flex flex-col">
      @forelse($topPrograms as $program)
        <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 last:border-b-0">
          <div class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold flex-shrink-0
                      {{ $loop->iteration <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
            {{ $loop->iteration }}
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-[13px] font-semibold text-slate-900 truncate">{{ $program->title }}</div>
            <div class="text-[11px] text-slate-400">{{ $program->completion_rate !== null ? $program->completion_rate . '% selesai' : 'Belum ada data' }}</div>
          </div>
          <div class="text-[13px] font-bold text-primary flex-shrink-0">{{ number_format($program->enrollments_count) }}</div>
        </div>
      @empty
        <div class="px-4 py-8 text-center text-[13px] text-slate-400">Belum ada data program.</div>
      @endforelse
    </div>
  </div>

  {{-- Booking per Status --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
      <div class="flex items-center gap-2.5">
        <div class="w-[30px] h-[30px] rounded-lg bg-blue-50 flex items-center justify-center">
          <svg class="w-[15px] h-[15px] text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <div class="text-[14px] font-bold text-slate-900">Booking per Status</div>
      </div>
    </div>
    <div class="p-5">
      @php
        $statusColors = [
          'pending'   => '#f59e0b',
          'confirmed' => '#1d9e75',
          'completed' => '#94a3b8',
          'cancelled' => '#ef4444',
        ];
      @endphp
      @forelse($bookingBreakdown as $row)
        <div class="{{ !$loop->last ? 'mb-4' : '' }}">
          <div class="flex justify-between text-[12px] mb-1.5">
            <span class="text-slate-600">{{ $row['label'] }}</span>
            <span class="font-bold text-slate-900">{{ $row['pct'] }}%</span>
          </div>
          <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width:{{ $row['pct'] }}%; background:{{ $statusColors[$row['key']] ?? '#94a3b8' }};"></div>
          </div>
        </div>
      @empty
        <div class="text-center text-[13px] text-slate-400 py-4">Belum ada booking pada periode ini.</div>
      @endforelse

      <div class="mt-4 pt-3.5 border-t border-slate-100 text-[12px] text-slate-400">
        Total <strong class="text-slate-700">{{ number_format($bookingPeriodTotal) }}</strong> booking dalam {{ $periodLabel }} terakhir
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     DISTRIBUSI ENROLLMENT PER LEVEL PROGRAM
══════════════════════════════════════════ --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
  <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
    <div class="flex items-center gap-2.5">
      <div class="w-[30px] h-[30px] rounded-lg bg-primary-light flex items-center justify-center">
        <svg class="w-[15px] h-[15px] text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
        </svg>
      </div>
      <div>
        <div class="text-[14px] font-bold text-slate-900">Distribusi Level Program</div>
        <div class="text-[11px] text-slate-400 mt-0.5">Sebaran enrollment member berdasarkan level kesulitan program</div>
      </div>
    </div>
  </div>

  @php
    $levelStyle = [
      'pemula'   => ['bg' => 'bg-primary-light', 'text' => 'text-primary',    'icon' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>'],
      'menengah' => ['bg' => 'bg-blue-50',        'text' => 'text-blue-500',  'icon' => '<path d="M6.5 6.5h11v11h-11z"/><path d="M6.5 6.5L3 3m11 11l3.5 3.5M17.5 6.5L21 3M6.5 17.5L3 21"/>'],
      'lanjutan' => ['bg' => 'bg-violet-50',      'text' => 'text-violet-500','icon' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>'],
    ];
  @endphp
  <div class="grid grid-cols-3 gap-4 p-5">
    @forelse($levelBreakdown as $row)
      @php $style = $levelStyle[$row['key']] ?? $levelStyle['pemula']; @endphp
      <div class="text-center p-4 bg-slate-50 rounded-lg">
        <div class="w-10 h-10 rounded-full {{ $style['bg'] }} flex items-center justify-center mx-auto mb-2.5">
          <svg class="w-5 h-5 {{ $style['text'] }}" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            {!! $style['icon'] !!}
          </svg>
        </div>
        <div class="text-[20px] font-extrabold text-slate-900">{{ $row['pct'] }}%</div>
        <div class="text-[11px] text-slate-400 mt-0.5">{{ $row['label'] }} · {{ number_format($row['count']) }} enrollment</div>
      </div>
    @empty
      <div class="col-span-3 text-center text-[13px] text-slate-400 py-6">Belum ada data enrollment.</div>
    @endforelse
  </div>
</div>

@endsection
