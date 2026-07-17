@extends('layouts.mentor')
@section('title', 'Program Latihan')

@section('content')

  <div class="flex items-end justify-between flex-wrap gap-4 mb-8">
    <div>
      <div class="text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">Manajemen Latihan</div>
      <h1 class="text-[28px] sm:text-3xl font-bold tracking-tight text-slate-900">Program Latihan</h1>
      <p class="text-sm text-slate-500 mt-1.5 max-w-md">Kelola program latihan, publikasikan video panduan baru, dan pantau progres member.</p>
    </div>
    <x-mentor.button :href="route('mentor.programs.create')">
      <x-mentor.icon name="plus" class="w-4 h-4" /> Buat Program Baru
    </x-mentor.button>
  </div>

  <div class="grid grid-cols-3 gap-4 mb-8">
    <x-mentor.stat-card label="Total Program" :value="$stats['total']" icon="dumbbell" />
    <x-mentor.stat-card label="Dipublikasikan" :value="$stats['published']" icon="check-circle" accent="primary" />
    <x-mentor.stat-card label="Draf" :value="$stats['draft']" icon="edit" accent="muted" />
  </div>

  <form method="GET" action="{{ route('mentor.programs.index') }}" class="flex items-center gap-3 flex-wrap mb-5">
    <div class="relative flex-1 min-w-[220px] max-w-sm">
      <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
        <x-mentor.icon name="search" class="w-4 h-4" />
      </div>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama program..."
             class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm placeholder-slate-400 bg-white focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-400 transition-all duration-150" />
    </div>

    <div class="flex items-center gap-1 bg-slate-100/70 rounded-xl p-1">
      @foreach (['' => 'Semua', 'published' => 'Live', 'draft' => 'Draf'] as $val => $label)
        <a href="{{ route('mentor.programs.index', array_filter(['q' => request('q'), 'status' => $val])) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ request('status', '') === $val ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    @if (request('q'))
      <button type="submit" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">Cari</button>
    @endif
  </form>

  @if ($programs->isEmpty())
    <x-mentor.empty-state
      icon="dumbbell"
      :title="request('q') || request('status') ? 'Tidak ada program yang cocok' : 'Belum ada program latihan'"
      :description="request('q') || request('status') ? 'Coba ubah kata kunci atau filter status.' : 'Mulai buat program latihan pertamamu untuk member.'"
    >
      <x-mentor.button :href="route('mentor.programs.create')">
        <x-mentor.icon name="plus" class="w-4 h-4" /> Buat Program Baru
      </x-mentor.button>
    </x-mentor.empty-state>
  @else
    {{-- Desktop / tablet: a single real <table> keeps header and body columns locked to the same widths,
         regardless of how much text is in any one row (a set of separate per-row grids cannot guarantee that). --}}
    <x-mentor.card padding="p-0" class="overflow-hidden hidden lg:block">
      <table class="w-full border-collapse table-fixed">
        <colgroup>
          <col style="width:34%">
          <col style="width:16%">
          <col style="width:22%">
          <col style="width:14%">
          <col style="width:14%">
        </colgroup>
        <thead>
          <tr class="bg-slate-50/70 text-[11px] font-bold text-slate-400 uppercase tracking-wide">
            <th class="text-left font-bold px-6 py-3">Program</th>
            <th class="text-left font-bold px-4 py-3">Status</th>
            <th class="text-left font-bold px-4 py-3">Progres Member</th>
            <th class="text-left font-bold px-4 py-3">Level</th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($programs as $program)
            @php $theme = $program->themeColor(); $avg = $program->averageProgress(); $count = $program->enrolledCount(); @endphp
            <tr class="border-t border-slate-100 hover:bg-slate-50/60 transition-colors">
              <td class="px-6 py-4 align-middle">
                <a href="{{ route('mentor.programs.show', $program) }}" class="flex items-center gap-3 min-w-0">
                  <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
                    <x-mentor.icon :name="$theme['icon']" class="w-4.5 h-4.5 text-white" />
                  </div>
                  <div class="min-w-0">
                    <div class="text-sm font-semibold truncate text-slate-900">{{ $program->title }}</div>
                    <div class="text-xs text-slate-500 truncate mt-0.5 flex items-center gap-1.5">
                      {{ $program->category }}
                      <span class="text-slate-300">·</span>
                      <span class="flex items-center gap-1 {{ $program->exercises_count === 0 ? 'text-amber-600' : '' }}">
                        <x-mentor.icon name="video" class="w-3 h-3" />
                        {{ $program->exercises_count }} latihan
                      </span>
                    </div>
                  </div>
                </a>
              </td>

              <td class="px-4 py-4 align-middle">
                <button form="toggle-{{ $program->id }}" type="submit">
                  <x-mentor.badge :variant="$program->isPublished() ? 'success' : 'neutral'" class="cursor-pointer hover:opacity-80 transition-opacity">
                    {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
                  </x-mentor.badge>
                </button>
                <form id="toggle-{{ $program->id }}" method="POST" action="{{ route('mentor.programs.toggle-status', $program) }}" class="hidden">
                  @csrf @method('PATCH')
                </form>
              </td>

              <td class="px-4 py-4 align-middle">
                <a href="{{ route('mentor.programs.show', $program) }}" class="block">
                  @if ($count === 0)
                    <span class="text-xs text-slate-400">Belum ada member</span>
                  @else
                    <div class="flex items-center gap-2.5">
                      <div class="w-20 shrink-0"><x-mentor.progress-bar :value="$avg ?? 0" /></div>
                      <span class="text-xs text-slate-500 shrink-0">{{ $count }} member</span>
                    </div>
                  @endif
                </a>
              </td>

              <td class="px-4 py-4 align-middle text-sm text-slate-600">{{ $program->levelLabel() }}</td>

              <td class="px-4 py-4 align-middle">
                <div class="flex items-center gap-2 justify-end">
                  <x-mentor.button :href="route('mentor.programs.show', $program)" variant="ghost" size="icon" title="Lihat Progres">
                    <x-mentor.icon name="bar-chart" class="w-3.5 h-3.5" />
                  </x-mentor.button>
                  <x-mentor.button :href="route('mentor.programs.edit', $program)" variant="secondary" size="icon" title="Edit">
                    <x-mentor.icon name="edit" class="w-3.5 h-3.5" />
                  </x-mentor.button>
                  <x-mentor.button variant="danger" size="icon" title="Hapus" type="button" onclick="document.getElementById('del-{{ $program->id }}').showModal()">
                    <x-mentor.icon name="trash" class="w-3.5 h-3.5" />
                  </x-mentor.button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </x-mentor.card>

    {{-- Mobile: stacked cards instead of squeezing a 5-column table --}}
    <div class="lg:hidden space-y-3">
      @foreach ($programs as $program)
        @php $theme = $program->themeColor(); $avg = $program->averageProgress(); $count = $program->enrolledCount(); @endphp
        <x-mentor.card padding="p-4">
          <div class="flex items-start justify-between gap-3">
            <a href="{{ route('mentor.programs.show', $program) }}" class="flex items-center gap-3 min-w-0">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
                <x-mentor.icon :name="$theme['icon']" class="w-4.5 h-4.5 text-white" />
              </div>
              <div class="min-w-0">
                <div class="text-sm font-semibold truncate text-slate-900">{{ $program->title }}</div>
                <div class="text-xs text-slate-500 truncate mt-0.5 flex items-center gap-1.5">
                  {{ $program->category }}
                  <span class="text-slate-300">·</span>
                  <span class="flex items-center gap-1 {{ $program->exercises_count === 0 ? 'text-amber-600' : '' }}">
                    <x-mentor.icon name="video" class="w-3 h-3" />
                    {{ $program->exercises_count }} latihan
                  </span>
                </div>
              </div>
            </a>
            <button form="toggle-{{ $program->id }}" type="submit" class="shrink-0">
              <x-mentor.badge :variant="$program->isPublished() ? 'success' : 'neutral'" class="cursor-pointer hover:opacity-80 transition-opacity">
                {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
              </x-mentor.badge>
            </button>
            <form id="toggle-{{ $program->id }}" method="POST" action="{{ route('mentor.programs.toggle-status', $program) }}" class="hidden">
              @csrf @method('PATCH')
            </form>
          </div>

          <div class="flex items-center justify-between gap-4 mt-4 pt-4 border-t border-slate-100">
            <a href="{{ route('mentor.programs.show', $program) }}" class="flex-1 min-w-0">
              @if ($count === 0)
                <span class="text-xs text-slate-400">Belum ada member</span>
              @else
                <div class="flex items-center gap-2.5">
                  <div class="w-20 shrink-0"><x-mentor.progress-bar :value="$avg ?? 0" /></div>
                  <span class="text-xs text-slate-500 shrink-0">{{ $count }} member</span>
                </div>
              @endif
            </a>
            <span class="text-xs font-medium text-slate-500 shrink-0">{{ $program->levelLabel() }}</span>
          </div>

          <div class="flex items-center gap-2 justify-end mt-3">
            <x-mentor.button :href="route('mentor.programs.show', $program)" variant="ghost" size="icon" title="Lihat Progres">
              <x-mentor.icon name="bar-chart" class="w-3.5 h-3.5" />
            </x-mentor.button>
            <x-mentor.button :href="route('mentor.programs.edit', $program)" variant="secondary" size="icon" title="Edit">
              <x-mentor.icon name="edit" class="w-3.5 h-3.5" />
            </x-mentor.button>
            <x-mentor.button variant="danger" size="icon" title="Hapus" type="button" onclick="document.getElementById('del-{{ $program->id }}').showModal()">
              <x-mentor.icon name="trash" class="w-3.5 h-3.5" />
            </x-mentor.button>
          </div>
        </x-mentor.card>
      @endforeach
    </div>

    {{-- Delete confirmation dialogs: rendered once outside the responsive lg:hidden/hidden lg:block
         blocks above, so `showModal()` still works no matter which breakpoint is active — a <dialog>
         nested in a display:none ancestor cannot be shown. --}}
    @foreach ($programs as $program)
      <dialog id="del-{{ $program->id }}" class="rounded-2xl p-0 w-full max-w-sm backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
        <form method="POST" action="{{ route('mentor.programs.destroy', $program) }}" class="p-6">
          @csrf @method('DELETE')
          <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mb-4">
            <x-mentor.icon name="trash" class="w-5 h-5" />
          </div>
          <h4 class="text-base font-bold text-slate-900 mb-1.5">Hapus program ini?</h4>
          <p class="text-sm text-slate-500 mb-6">"{{ $program->title }}" akan dihapus permanen beserta seluruh data progres member terkait.</p>
          <div class="flex justify-end gap-2.5">
            <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('del-{{ $program->id }}').close()">Batal</x-mentor.button>
            <x-mentor.button type="submit" variant="danger-solid">Ya, Hapus</x-mentor.button>
          </div>
        </form>
      </dialog>
    @endforeach

    <div class="mt-6">{{ $programs->links() }}</div>
  @endif

@endsection
