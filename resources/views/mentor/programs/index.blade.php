@extends('layouts.mentor')
@section('title', 'Program Latihan')

@section('content')

  <div class="flex items-end justify-between flex-wrap gap-4 mb-8">
    <div>
      <div class="text-xs font-bold text-primary-dark tracking-widest uppercase mb-2">Manajemen Latihan</div>
      <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Program Latihan</h1>
      <p class="text-sm text-slate-500 mt-1.5 max-w-md">Kelola program latihan, publikasikan video panduan baru, dan pantau performanya.</p>
    </div>
    <a href="{{ route('mentor.programs.create') }}"
       class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:-translate-y-px transition-all">
      @include('mentor.partials.icon', ['name' => 'plus', 'class' => 'w-4 h-4'])
      Buat Program Baru
    </a>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <div class="text-xs text-slate-500 mb-1.5">Total Program</div>
      <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <div class="text-xs text-slate-500 mb-1.5">Dipublikasikan</div>
      <div class="text-2xl font-bold text-primary">{{ $stats['published'] }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <div class="text-xs text-slate-500 mb-1.5">Draf</div>
      <div class="text-2xl font-bold text-slate-400">{{ $stats['draft'] }}</div>
    </div>
  </div>

  {{-- Filter / search toolbar --}}
  <form method="GET" action="{{ route('mentor.programs.index') }}" class="flex items-center gap-3 flex-wrap mb-5">
    <div class="relative flex-1 min-w-[220px] max-w-sm">
      <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
        @include('mentor.partials.icon', ['name' => 'search', 'class' => 'w-4 h-4'])
      </div>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama program..."
             class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition" />
    </div>

    <div class="flex items-center gap-1 bg-white border border-slate-200 rounded-xl p-1">
      @foreach (['' => 'Semua', 'published' => 'Live', 'draft' => 'Draf'] as $val => $label)
        <a href="{{ route('mentor.programs.index', array_filter(['q' => request('q'), 'status' => $val])) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status', '') === $val ? 'bg-primary text-white' : 'text-slate-500 hover:text-slate-900' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    @if (request('q'))
      <button type="submit" class="text-sm font-semibold text-primary-dark hover:underline">Cari</button>
    @endif
  </form>

  {{-- Table --}}
  @if ($programs->isEmpty())
    <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-14 text-center">
      @include('mentor.partials.icon', ['name' => 'dumbbell', 'class' => 'w-8 h-8 text-slate-300 mx-auto mb-4'])
      <h4 class="text-sm font-bold mb-1.5">
        {{ request('q') || request('status') ? 'Tidak ada program yang cocok' : 'Belum ada program latihan' }}
      </h4>
      <p class="text-sm text-slate-500 mb-6">
        {{ request('q') || request('status') ? 'Coba ubah kata kunci atau filter status.' : 'Mulai buat program latihan pertamamu untuk member.' }}
      </p>
      <a href="{{ route('mentor.programs.create') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
        @include('mentor.partials.icon', ['name' => 'plus', 'class' => 'w-4 h-4']) Buat Program Baru
      </a>
    </div>
  @else
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
      <div class="hidden md:grid grid-cols-[2fr_1fr_1fr_1fr_auto] gap-4 px-6 py-3.5 bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wide">
        <div>Program</div><div>Status</div><div>Level</div><div>Durasi</div><div></div>
      </div>

      @foreach ($programs as $program)
        @php $theme = $program->themeColor(); @endphp
        <div class="grid md:grid-cols-[2fr_1fr_1fr_1fr_auto] gap-3 md:gap-4 items-center px-6 py-4 border-t border-slate-100">

          <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
              @include('mentor.partials.icon', ['name' => $theme['icon'], 'class' => 'w-4.5 h-4.5 text-white'])
            </div>
            <div class="min-w-0">
              <div class="text-sm font-semibold truncate">{{ $program->title }}</div>
              <div class="text-xs text-slate-500 truncate">{{ $program->category }}</div>
            </div>
          </div>

          <div>
            <button form="toggle-{{ $program->id }}" type="submit"
                    class="text-[11px] font-bold px-2.5 py-1 rounded-full transition {{ $program->isPublished() ? 'bg-primary-light text-primary-dark hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
              {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
            </button>
            <form id="toggle-{{ $program->id }}" method="POST" action="{{ route('mentor.programs.toggle-status', $program) }}" class="hidden">
              @csrf @method('PATCH')
            </form>
          </div>

          <div class="text-sm text-slate-600">{{ $program->levelLabel() }}</div>
          <div class="text-sm text-slate-600">{{ $program->duration_weeks ? $program->duration_weeks . ' minggu' : '—' }}</div>

          <div class="flex items-center gap-2 justify-end md:justify-start">
            <a href="{{ route('mentor.programs.edit', $program) }}" title="Edit"
               class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition">
              @include('mentor.partials.icon', ['name' => 'edit', 'class' => 'w-3.5 h-3.5'])
            </a>
            <button type="button" onclick="document.getElementById('del-{{ $program->id }}').showModal()" title="Hapus"
               class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:text-red-600 transition">
              @include('mentor.partials.icon', ['name' => 'trash', 'class' => 'w-3.5 h-3.5'])
            </button>
          </div>
        </div>

        {{-- Delete confirmation dialog --}}
        <dialog id="del-{{ $program->id }}" class="rounded-2xl p-0 w-full max-w-sm backdrop:bg-slate-900/40">
          <form method="POST" action="{{ route('mentor.programs.destroy', $program) }}" class="p-6">
            @csrf @method('DELETE')
            <h4 class="text-base font-bold mb-2">Hapus program ini?</h4>
            <p class="text-sm text-slate-500 mb-6">"{{ $program->title }}" akan dihapus permanen dan tidak dapat dikembalikan.</p>
            <div class="flex justify-end gap-3">
              <button type="button" onclick="document.getElementById('del-{{ $program->id }}').close()"
                      class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
              <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold bg-red-600 hover:bg-red-700 text-white transition">Ya, Hapus</button>
            </div>
          </form>
        </dialog>
      @endforeach
    </div>

    <div class="mt-6">{{ $programs->links() }}</div>
  @endif

@endsection
