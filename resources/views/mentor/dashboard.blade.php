@extends('layouts.mentor')
@section('title', 'Dashboard')

@section('content')

  {{-- Hero greeting --}}
  <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-ink-900 via-ink-900 to-primary-900 px-6 sm:px-10 py-9 sm:py-11 mb-10 shadow-elevated animate-fade-in-up">
    <div class="absolute -top-16 -right-10 w-72 h-72 orb animate-orb-float-slow opacity-70"></div>
    <div class="absolute -bottom-24 left-1/4 w-64 h-64 orb-mini animate-orb-float opacity-40" style="animation-delay:-4s"></div>
    <div class="absolute inset-0 noise-overlay"></div>

    <div class="relative flex items-end justify-between flex-wrap gap-6">
      <div>
        <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-primary-300/90 tracking-widest uppercase mb-3">
          <x-mentor.icon name="sparkles" class="w-3.5 h-3.5" /> Ringkasan
        </div>
        <h1 class="display-heading text-[28px] sm:text-4xl font-extrabold text-white">Halo, {{ $mentor->full_name }} 👋</h1>
        <p class="text-[15px] text-white/60 mt-2.5 max-w-md">Ini yang terjadi di programmu hari ini.</p>
      </div>
      <x-mentor.button :href="route('mentor.programs.create')" size="lg" magnetic>
        <x-mentor.icon name="plus" class="w-4 h-4" /> Buat Program Baru
      </x-mentor.button>
    </div>
  </div>

  {{-- Stat cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
    <x-mentor.stat-card label="Total Program" :value="$stats['programs_total']" icon="dumbbell" />
    <x-mentor.stat-card label="Dipublikasikan" :value="$stats['programs_published']" icon="check-circle" accent="primary" />
    <x-mentor.stat-card
      label="Rata-rata Penyelesaian"
      :value="$stats['avg_progress'] !== null ? number_format($stats['avg_progress'], 1) . '%' : '—'"
      icon="trending-up"
      accent="primary"
      :sub="$stats['unique_members'] . ' member aktif'"
    />
    <x-mentor.stat-card label="Menunggu Konfirmasi" :value="$stats['bookings_pending']" icon="clock" accent="{{ $stats['bookings_pending'] > 0 ? 'warning' : 'default' }}" />
  </div>

  <div class="grid lg:grid-cols-2 gap-8 mb-8">

    {{-- Recent programs --}}
    <div>
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-bold text-slate-900">Program Terbaru</h3>
        <a href="{{ route('mentor.programs.index') }}" class="text-[13px] font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1 group">
          Lihat semua <x-mentor.icon name="chevron-right" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" />
        </a>
      </div>

      @if ($recentPrograms->isEmpty())
        <x-mentor.empty-state icon="dumbbell" title="Belum ada program latihan" description="Buat program pertamamu agar member bisa mulai berlatih.">
          <x-mentor.button size="sm" :href="route('mentor.programs.create')">
            <x-mentor.icon name="plus" class="w-3.5 h-3.5" /> Buat Program
          </x-mentor.button>
        </x-mentor.empty-state>
      @else
        <x-mentor.card padding="p-0" class="overflow-hidden divide-y divide-slate-100">
          @foreach ($recentPrograms as $program)
            @php $theme = $program->themeColor(); $avg = $program->averageProgress(); @endphp
            <a href="{{ route('mentor.programs.show', $program) }}" class="group flex items-center gap-3.5 px-5 py-4 hover:bg-slate-50 transition-colors relative">
              <span class="absolute left-0 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary-400 to-primary-600 scale-y-0 group-hover:scale-y-100 transition-transform duration-200 origin-center"></span>
              <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm transition-transform duration-200 group-hover:scale-105" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
                <x-mentor.icon :name="$theme['icon']" class="w-4.5 h-4.5 text-white" />
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate text-slate-900">{{ $program->title }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $program->category }} · {{ $program->levelLabel() }}</div>
              </div>
              @if ($avg !== null)
                <div class="hidden sm:block w-20 shrink-0">
                  <x-mentor.progress-bar :value="$avg" />
                </div>
              @endif
              <x-mentor.badge :variant="$program->isPublished() ? 'success' : 'neutral'" :dot="true" class="shrink-0">
                {{ $program->isPublished() ? 'Live' : 'Draf' }}
              </x-mentor.badge>
            </a>
          @endforeach
        </x-mentor.card>
      @endif
    </div>

    {{-- Pending bookings --}}
    <div>
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-bold text-slate-900">Permintaan Booking Masuk</h3>
        <a href="{{ route('mentor.bookings.index') }}" class="text-[13px] font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1 group">
          Lihat semua <x-mentor.icon name="chevron-right" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" />
        </a>
      </div>

      @if ($pendingBookings->isEmpty())
        <x-mentor.empty-state icon="inbox" title="Tidak ada permintaan baru" description="Permintaan booking dari member akan muncul di sini." />
      @else
        <x-mentor.card padding="p-0" class="overflow-hidden divide-y divide-slate-100">
          @foreach ($pendingBookings as $booking)
            <div class="flex items-center gap-3.5 px-5 py-4 hover:bg-slate-50/70 transition-colors">
              <x-mentor.avatar :name="$booking->member->full_name ?? 'Member'" tone="amber" />
              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate text-slate-900">{{ $booking->member->full_name ?? 'Member' }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $booking->scheduled_at->translatedFormat('d M Y · H:i') }} WIB</div>
              </div>
              <x-mentor.badge variant="warning" :dot="true">Pending</x-mentor.badge>
            </div>
          @endforeach
        </x-mentor.card>
      @endif
    </div>
  </div>

  {{-- Needs attention --}}
  <div>
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
        Member Perlu Perhatian
        @if ($attentionList->count())
          <x-mentor.badge variant="warning">{{ $attentionList->count() }}</x-mentor.badge>
        @endif
      </h3>
      <a href="{{ route('mentor.statistics.index') }}" class="text-[13px] font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1 group">
        Lihat statistik <x-mentor.icon name="chevron-right" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5" />
      </a>
    </div>

    @if ($attentionList->isEmpty())
      <x-mentor.empty-state icon="check-circle" title="Semua member on-track" description="Tidak ada member yang stagnan dalam 7 hari terakhir. Kerja bagus!" />
    @else
      <div class="grid sm:grid-cols-2 gap-3">
        @foreach ($attentionList as $enrollment)
          <x-mentor.card padding="p-4" hover class="flex items-center gap-3.5">
            <x-mentor.avatar :name="$enrollment->member->full_name ?? 'Member'" tone="neutral" />
            <div class="flex-1 min-w-0">
              <div class="text-sm font-semibold truncate text-slate-900">{{ $enrollment->member->full_name ?? 'Member' }}</div>
              <div class="text-xs text-slate-500 truncate mt-0.5">{{ $enrollment->workoutProgram->title }}</div>
              <div class="mt-2"><x-mentor.progress-bar :value="$enrollment->progress_pct" show-label /></div>
            </div>
          </x-mentor.card>
        @endforeach
      </div>
    @endif
  </div>

@endsection
