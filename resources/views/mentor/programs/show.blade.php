@extends('layouts.mentor')
@section('title', $program->title)

@section('content')

  @php $theme = $program->themeColor(); @endphp

  <div class="mb-8">
    <a href="{{ route('mentor.programs.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors mb-5">
      <x-mentor.icon name="arrow-left" class="w-4 h-4" /> Kembali ke Program Latihan
    </a>

    <div class="flex items-start justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
          <x-mentor.icon :name="$theme['icon']" class="w-6 h-6 text-white" />
        </div>
        <div>
          <div class="flex items-center gap-2.5 flex-wrap">
            <h1 class="text-2xl sm:text-[28px] font-bold tracking-tight text-slate-900">{{ $program->title }}</h1>
            <x-mentor.badge :variant="$program->isPublished() ? 'success' : 'neutral'">
              {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
            </x-mentor.badge>
          </div>
          <p class="text-sm text-slate-500 mt-1">{{ $program->category }} · {{ $program->levelLabel() }} @if($program->duration_weeks) · {{ $program->duration_weeks }} minggu @endif</p>
        </div>
      </div>
      <x-mentor.button :href="route('mentor.programs.edit', $program)" variant="secondary">
        <x-mentor.icon name="edit" class="w-4 h-4" /> Edit Program
      </x-mentor.button>
    </div>

    @if ($program->exercises->isNotEmpty())
      <div class="flex items-center gap-1.5 flex-wrap mt-5">
        @foreach ($program->exercises as $exercise)
          <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100/70 pl-2.5 pr-3 py-1.5 rounded-full">
            @if ($exercise->video_url)
              <x-mentor.icon name="video" class="w-3 h-3 text-primary-500" />
            @endif
            {{ $exercise->name }}
          </span>
        @endforeach
      </div>
    @else
      <p class="text-xs text-slate-400 mt-5">Program ini belum punya daftar latihan — <a href="{{ route('mentor.programs.edit', $program) }}" class="text-primary-600 font-semibold hover:underline">tambahkan di halaman edit</a>.</p>
    @endif
  </div>

  {{-- Stat cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
    <x-mentor.stat-card label="Member Terdaftar" :value="$stats['enrolled']" icon="users" />
    <x-mentor.stat-card
      label="Rata-rata Progres"
      :value="$stats['avg_progress'] !== null ? number_format($stats['avg_progress'], 1) . '%' : '—'"
      icon="trending-up" accent="primary"
    />
    <x-mentor.stat-card
      label="Tingkat Penyelesaian"
      :value="$stats['completion_rate'] !== null ? number_format($stats['completion_rate'], 1) . '%' : '—'"
      icon="check-circle" accent="primary"
    />
    <x-mentor.stat-card
      label="Perlu Perhatian"
      :value="$stats['needs_attention']"
      icon="alert-triangle"
      :accent="$stats['needs_attention'] > 0 ? 'warning' : 'default'"
    />
  </div>

  {{-- Member progress list --}}
  <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
    <h3 class="text-base font-bold text-slate-900">Progres Member</h3>

    <div class="flex items-center gap-2 flex-wrap">
      <div class="flex items-center gap-1 bg-slate-100/70 rounded-xl p-1">
        @foreach (['' => 'Semua', 'active' => 'Aktif', 'completed' => 'Selesai', 'attention' => 'Perlu Perhatian'] as $val => $label)
          <a href="{{ route('mentor.programs.show', array_filter(['program' => $program->id, 'filter' => $val, 'sort' => request('sort')])) }}"
             class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request('filter', '') === $val ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
            {{ $label }}
          </a>
        @endforeach
      </div>

      <div class="relative">
        <select onchange="window.location.href=this.value"
                class="appearance-none pl-3 pr-8 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-600 focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-400 transition-all">
          @php $sortOptions = ['progress_desc' => 'Progres Tertinggi', 'progress_asc' => 'Progres Terendah', 'oldest' => 'Terlama Bergabung']; @endphp
          @foreach ($sortOptions as $val => $label)
            <option value="{{ route('mentor.programs.show', array_filter(['program' => $program->id, 'filter' => request('filter'), 'sort' => $val])) }}" @selected(request('sort', 'progress_desc') === $val)>
              {{ $label }}
            </option>
          @endforeach
        </select>
        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
          <x-mentor.icon name="chevron-down" class="w-3.5 h-3.5" />
        </div>
      </div>
    </div>
  </div>

  @if ($enrollments->isEmpty())
    <x-mentor.empty-state
      icon="users"
      :title="request('filter') ? 'Tidak ada member yang cocok dengan filter ini' : 'Belum ada member yang mengambil program ini'"
      :description="request('filter') ? 'Coba ubah filter di atas.' : 'Statistik progres akan muncul otomatis begitu member mulai menjalani program ini.'"
    />
  @else
    {{-- md+ : a single real <table> so header and body columns are always locked to the same widths --}}
    <x-mentor.card padding="p-0" class="overflow-hidden hidden md:block">
      <table class="w-full border-collapse table-fixed">
        <colgroup>
          <col style="width:32%">
          <col style="width:30%">
          <col style="width:18%">
          <col style="width:20%">
        </colgroup>
        <thead>
          <tr class="bg-slate-50/70 text-[11px] font-bold text-slate-400 uppercase tracking-wide">
            <th class="text-left font-bold px-6 py-3">Member</th>
            <th class="text-left font-bold px-4 py-3">Progres</th>
            <th class="text-left font-bold px-4 py-3">Status</th>
            <th class="text-left font-bold px-4 py-3">Aktivitas Terakhir</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($enrollments as $enrollment)
            <tr class="border-t border-slate-100">
              <td class="px-6 py-4 align-middle">
                <div class="flex items-center gap-3 min-w-0">
                  <x-mentor.avatar :name="$enrollment->member->full_name ?? 'Member'" tone="neutral" />
                  <div class="min-w-0">
                    <div class="text-sm font-semibold truncate text-slate-900">{{ $enrollment->member->full_name ?? 'Member' }}</div>
                    <div class="text-xs text-slate-400 mt-0.5">Sejak {{ $enrollment->started_at->translatedFormat('d M Y') }}</div>
                  </div>
                </div>
              </td>

              <td class="px-4 py-4 align-middle">
                <x-mentor.progress-bar :value="$enrollment->progress_pct" show-label />
              </td>

              <td class="px-4 py-4 align-middle">
                @if ($enrollment->isCompleted())
                  <x-mentor.badge variant="success">Selesai</x-mentor.badge>
                @elseif ($enrollment->needsAttention())
                  <x-mentor.badge variant="warning">Perlu Perhatian</x-mentor.badge>
                @else
                  <x-mentor.badge variant="info">Aktif</x-mentor.badge>
                @endif
              </td>

              <td class="px-4 py-4 align-middle text-xs text-slate-500">
                {{ $enrollment->last_activity_at?->diffForHumans() ?? 'Belum pernah aktif' }}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </x-mentor.card>

    {{-- Mobile: stacked cards --}}
    <div class="md:hidden space-y-3">
      @foreach ($enrollments as $enrollment)
        <x-mentor.card padding="p-4">
          <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
              <x-mentor.avatar :name="$enrollment->member->full_name ?? 'Member'" tone="neutral" />
              <div class="min-w-0">
                <div class="text-sm font-semibold truncate text-slate-900">{{ $enrollment->member->full_name ?? 'Member' }}</div>
                <div class="text-xs text-slate-400 mt-0.5">Sejak {{ $enrollment->started_at->translatedFormat('d M Y') }}</div>
              </div>
            </div>
            @if ($enrollment->isCompleted())
              <x-mentor.badge variant="success" class="shrink-0">Selesai</x-mentor.badge>
            @elseif ($enrollment->needsAttention())
              <x-mentor.badge variant="warning" class="shrink-0">Perlu Perhatian</x-mentor.badge>
            @else
              <x-mentor.badge variant="info" class="shrink-0">Aktif</x-mentor.badge>
            @endif
          </div>

          <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
            <div class="flex-1"><x-mentor.progress-bar :value="$enrollment->progress_pct" show-label /></div>
          </div>
          <div class="text-xs text-slate-500 mt-2">
            {{ $enrollment->last_activity_at?->diffForHumans() ?? 'Belum pernah aktif' }}
          </div>
        </x-mentor.card>
      @endforeach
    </div>

    <div class="mt-6">{{ $enrollments->links() }}</div>
  @endif

@endsection
