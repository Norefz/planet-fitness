@extends('layouts.mentor')
@section('title', 'Statistik')

@section('content')

  <div class="mb-8">
    <div class="text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">Analitik</div>
    <h1 class="text-[28px] sm:text-3xl font-bold tracking-tight text-slate-900">Statistik Progres Member</h1>
    <p class="text-sm text-slate-500 mt-1.5 max-w-lg">Rata-rata penyelesaian member secara keseluruhan, dan perbandingan performa di setiap program latihan.</p>
  </div>

  {{-- Overall stats --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
    <x-mentor.stat-card label="Total Member Unik" :value="$overall['unique_members']" icon="users" />
    <x-mentor.stat-card
      label="Rata-rata Progres Keseluruhan"
      :value="$overall['avg_progress'] !== null ? number_format($overall['avg_progress'], 1) . '%' : '—'"
      icon="trending-up" accent="primary"
    />
    <x-mentor.stat-card
      label="Tingkat Penyelesaian"
      :value="$overall['completion_rate'] !== null ? number_format($overall['completion_rate'], 1) . '%' : '—'"
      icon="check-circle" accent="primary"
    />
    <x-mentor.stat-card
      label="Perlu Perhatian"
      :value="$overall['needs_attention']"
      icon="alert-triangle"
      :accent="$overall['needs_attention'] > 0 ? 'warning' : 'default'"
    />
  </div>

  <div class="grid lg:grid-cols-5 gap-8">

    {{-- Per-program comparison --}}
    <div class="lg:col-span-3">
      <h3 class="text-base font-bold text-slate-900 mb-4">Perbandingan Antar Program</h3>

      @if ($programs->isEmpty())
        <x-mentor.empty-state icon="dumbbell" title="Belum ada program" description="Buat program latihan untuk mulai melihat statistiknya di sini.">
          <x-mentor.button size="sm" :href="route('mentor.programs.create')">
            <x-mentor.icon name="plus" class="w-3.5 h-3.5" /> Buat Program
          </x-mentor.button>
        </x-mentor.empty-state>
      @else
        <div class="flex flex-col gap-3">
          @foreach ($programs as $program)
            @php $theme = $program->themeColor(); $avg = $program->averageProgress(); $count = $program->enrolled_count ?? $program->enrolledCount(); @endphp
            <a href="{{ route('mentor.programs.show', $program) }}" class="block bg-white border border-slate-200 rounded-2xl p-5 transition-all duration-150 hover:shadow-md hover:border-slate-300">
              <div class="flex items-center gap-3.5 mb-3.5">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
                  <x-mentor.icon :name="$theme['icon']" class="w-4 h-4 text-white" />
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-sm font-semibold truncate text-slate-900">{{ $program->title }}</div>
                  <div class="text-xs text-slate-400 mt-0.5">{{ $count }} member · {{ $program->completionRate() !== null ? number_format($program->completionRate(), 0) . '% selesai' : 'Belum ada data' }}</div>
                </div>
                <div class="text-lg font-bold text-slate-900 tabular-nums shrink-0">
                  {{ $avg !== null ? number_format($avg, 0) . '%' : '—' }}
                </div>
              </div>
              <x-mentor.progress-bar :value="$avg ?? 0" height="h-1.5" />
            </a>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Needs attention --}}
    <div class="lg:col-span-2">
      <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
        Perlu Perhatian
        @if ($attentionList->count())
          <x-mentor.badge variant="warning">{{ $attentionList->count() }}</x-mentor.badge>
        @endif
      </h3>

      @if ($attentionList->isEmpty())
        <x-mentor.empty-state icon="check-circle" title="Semua member on-track" description="Tidak ada yang stagnan lebih dari 7 hari. Kerja bagus!" />
      @else
        <div class="flex flex-col gap-3">
          @foreach ($attentionList as $enrollment)
            <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center gap-3">
              <x-mentor.avatar :name="$enrollment->member->full_name ?? 'Member'" tone="amber" size="sm" />
              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate text-slate-900">{{ $enrollment->member->full_name ?? 'Member' }}</div>
                <div class="text-xs text-slate-500 truncate mt-0.5">{{ $enrollment->workoutProgram->title }} · {{ $enrollment->progress_pct }}%</div>
              </div>
              <span class="text-[11px] text-slate-400 shrink-0 text-right">
                {{ $enrollment->last_activity_at?->diffForHumans(null, true) ?? 'belum pernah' }}{{ $enrollment->last_activity_at ? ' lalu' : '' }}
              </span>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

@endsection
