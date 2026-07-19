@extends('admin.layouts.app')

@section('title', 'Program Latihan')
@section('page_title', 'Program Latihan')
@section('page_subtitle', 'Kelola Semua Program')

@section('content')

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Program Latihan</h1>
    <p class="text-[13px] text-slate-500 mt-0.5">
      {{ $stats['published'] }} program dipublikasikan · {{ $stats['draft'] }} draf · dipublikasikan oleh {{ $stats['contributing_mentors'] }} mentor
    </p>
  </div>
</div>

{{-- ══════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-4 gap-4 mb-6">

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#8b5cf6;">
    <div class="w-10 h-10 rounded-[10px] bg-violet-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['published']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Program Dipublikasikan</div>
      <div class="text-[11px] text-slate-400 mt-0.5">{{ $stats['draft'] }} masih draf</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#1d9e75;">
    <div class="w-10 h-10 rounded-[10px] bg-primary-light flex items-center justify-center">
      <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ number_format($stats['total_enrollments']) }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Total Enrollment</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Member mengikuti program</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#3b82f6;">
    <div class="w-10 h-10 rounded-[10px] bg-blue-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">
        {{ $stats['completion_rate'] !== null ? number_format($stats['completion_rate'], 1) . '%' : '—' }}
      </div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Rata-rata Penyelesaian</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Dari semua enrollment</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#f59e0b;">
    <div class="w-10 h-10 rounded-[10px] bg-amber-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $stats['contributing_mentors'] }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Mentor Berkontribusi</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Punya program terpublikasi</div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════
     FILTER BAR
══════════════════════════════════════════ --}}
<form method="GET" action="{{ route('admin.programs') }}" class="flex items-center gap-3 flex-wrap mb-5">
  <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 cursor-pointer">
    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draf</option>
  </select>

  <select name="category" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 cursor-pointer">
    <option value="all" {{ $category === 'all' ? 'selected' : '' }}>Semua Kategori</option>
    @foreach($categories as $cat)
      <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
    @endforeach
  </select>

  <select name="level" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 cursor-pointer">
    <option value="all" {{ $level === 'all' ? 'selected' : '' }}>Semua Tingkat</option>
    <option value="pemula" {{ $level === 'pemula' ? 'selected' : '' }}>Pemula</option>
    <option value="menengah" {{ $level === 'menengah' ? 'selected' : '' }}>Menengah</option>
    <option value="lanjutan" {{ $level === 'lanjutan' ? 'selected' : '' }}>Lanjutan</option>
  </select>

  <select name="sort" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 cursor-pointer">
    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Urutkan: Terpopuler</option>
    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
    <option value="completion" {{ $sort === 'completion' ? 'selected' : '' }}>Urutkan: Penyelesaian Terbanyak</option>
  </select>

  <div class="flex items-center gap-2 bg-slate-100 border border-slate-200 rounded-lg px-3.5 py-2 w-64">
    <svg class="w-[15px] h-[15px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama program, mentor, kategori..."
           class="border-none bg-transparent text-[13px] text-slate-700 outline-none w-full placeholder:text-slate-400" />
  </div>

  <div class="text-[12px] text-slate-400 ml-auto">
    Menampilkan {{ $programs->firstItem() ?? 0 }}–{{ $programs->lastItem() ?? 0 }} dari {{ $programs->total() }} program
  </div>
</form>

{{-- ══════════════════════════════════════════
     PROGRAM CARDS GRID
══════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
  @forelse($programs as $program)
    @php $theme = $program->themeColor(); @endphp
    <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden flex flex-col">
      <div class="relative h-28 flex items-center justify-center" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
        <x-mentor.icon :name="$theme['icon']" class="w-6 h-6 text-white" />
        <span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold text-white"
              style="background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3);">
          {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
        </span>
      </div>

      <div class="p-4 flex-1 flex flex-col gap-3">
        <div>
          <a href="{{ route('admin.programs.show', $program) }}" class="text-[14px] font-bold text-slate-900 hover:underline no-underline block">
            {{ $program->title }}
          </a>
          <p class="text-[11px] text-slate-400 mt-0.5">
            oleh {{ $program->mentor->full_name ?? '—' }}
            @if($program->mentor?->certification) · {{ $program->mentor->certification }} @endif
          </p>
        </div>

        <div class="flex items-center gap-3 text-[11px] text-slate-500">
          <span class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            {{ $program->enrollments_count }} member
          </span>
          <span class="flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            {{ $program->enrollments_count > 0 ? round(($program->completed_enrollments_count / $program->enrollments_count) * 100) . '% selesai' : 'Belum ada data' }}
          </span>
        </div>

        <div class="flex gap-1.5 flex-wrap">
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">{{ $program->levelLabel() }}</span>
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">{{ $program->category }}</span>
          @if($program->duration_weeks)
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">{{ $program->duration_weeks }} minggu</span>
          @endif
        </div>
      </div>

      <div class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
        <span class="text-[11px] text-slate-400">Diperbarui {{ $program->updated_at->diffForHumans() }}</span>
        <div class="flex items-center gap-1.5">
          <a href="{{ route('admin.programs.show', $program) }}" title="Lihat"
             class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all duration-200 no-underline">
            <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </a>

          <form method="POST" action="{{ route('admin.programs.toggle', $program) }}">
            @csrf @method('PATCH')
            <button type="submit" title="{{ $program->isPublished() ? 'Arsipkan (kembalikan ke draf)' : 'Publikasikan' }}"
                    class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center transition-all duration-200 cursor-pointer
                           {{ $program->isPublished() ? 'text-amber-600 hover:bg-amber-50' : 'text-primary hover:bg-primary-light' }}">
              @if($program->isPublished())
                <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
              @else
                <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              @endif
            </button>
          </form>

          <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" onsubmit="return confirm('Hapus program ini secara permanen?');">
            @csrf @method('DELETE')
            <button type="submit" title="Hapus"
                    class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-red-500 hover:bg-red-50 transition-all duration-200 cursor-pointer">
              <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6m4-6v6"/></svg>
            </button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="col-span-full bg-white border border-slate-200 rounded-xl px-5 py-10 text-center text-[13px] text-slate-400">
      Tidak ada program yang cocok dengan pencarian/filter ini.
    </div>
  @endforelse
</div>

@if($programs->hasPages())
  <div class="mt-6">
    {{ $programs->links() }}
  </div>
@endif

@endsection
