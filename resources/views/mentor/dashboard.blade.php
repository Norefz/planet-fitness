@extends('layouts.mentor')
@section('title', 'Dashboard')

@section('content')

  <div class="flex items-end justify-between flex-wrap gap-4 mb-8">
    <div>
      <div class="text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">Ringkasan</div>
      <h1 class="text-[28px] sm:text-3xl font-bold tracking-tight text-slate-900">Halo, {{ $mentor->full_name }} 👋</h1>
      <p class="text-sm text-slate-500 mt-1.5">Ini yang terjadi di programmu hari ini.</p>
    </div>
    <x-mentor.button :href="route('mentor.programs.create')">
      <x-mentor.icon name="plus" class="w-4 h-4" /> Buat Program Baru
    </x-mentor.button>
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
        <a href="{{ route('mentor.programs.index') }}" class="text-[13px] font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1">
          Lihat semua <x-mentor.icon name="chevron-right" class="w-3.5 h-3.5" />
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
            <a href="{{ route('mentor.programs.show', $program) }}" class="flex items-center gap-3.5 px-5 py-4 hover:bg-slate-50 transition-colors">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
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
              <x-mentor.badge :variant="$program->isPublished() ? 'success' : 'neutral'" class="shrink-0">
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
        <a href="{{ route('mentor.bookings.index') }}" class="text-[13px] font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1">
          Lihat semua <x-mentor.icon name="chevron-right" class="w-3.5 h-3.5" />
        </a>
      </div>

      @if ($pendingBookings->isEmpty())
        <x-mentor.empty-state icon="inbox" title="Tidak ada permintaan baru" description="Permintaan booking dari member akan muncul di sini." />
      @else
        <x-mentor.card padding="p-0" class="overflow-hidden divide-y divide-slate-100">
          @foreach ($pendingBookings as $booking)
            <div class="flex items-center gap-3.5 px-5 py-4">
              <x-mentor.avatar :name="$booking->member->full_name ?? 'Member'" tone="amber" />
              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate text-slate-900">{{ $booking->member->full_name ?? 'Member' }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $booking->scheduled_at->translatedFormat('d M Y · H:i') }} WIB</div>
              </div>
              <x-mentor.badge variant="warning">Pending</x-mentor.badge>
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
      <a href="{{ route('mentor.statistics.index') }}" class="text-[13px] font-semibold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-1">
        Lihat statistik <x-mentor.icon name="chevron-right" class="w-3.5 h-3.5" />
      </a>
    </div>

    @if ($attentionList->isEmpty())
      <x-mentor.empty-state icon="check-circle" title="Semua member on-track" description="Tidak ada member yang stagnan dalam 7 hari terakhir. Kerja bagus!" />
    @else
      <div class="grid sm:grid-cols-2 gap-3">
        @foreach ($attentionList as $enrollment)
          <x-mentor.card padding="p-4" class="flex items-center gap-3.5">
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
