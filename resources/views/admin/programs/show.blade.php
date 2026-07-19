@extends('admin.layouts.app')

@section('title', $program->title)
@section('page_title', 'Program Latihan')
@section('page_subtitle', $program->title)

@section('content')

@php $theme = $program->themeColor(); @endphp

<a href="{{ route('admin.programs') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 no-underline mb-5">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
  Kembali ke Program Latihan
</a>

<div class="flex items-start justify-between flex-wrap gap-4 mb-6">
  <div class="flex items-center gap-4">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0"
         style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
      <x-mentor.icon :name="$theme['icon']" class="w-7 h-7 text-white" />
    </div>
    <div>
      <div class="flex items-center gap-2.5 flex-wrap">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $program->title }}</h1>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold
                     {{ $program->isPublished() ? 'bg-primary-light text-primary-dark' : 'bg-slate-100 text-slate-500' }}">
          {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
        </span>
      </div>
      <p class="text-sm text-slate-500 mt-1">
        oleh {{ $program->mentor->full_name ?? '—' }}
        @if($program->mentor?->certification) · {{ $program->mentor->certification }} @endif
        · {{ $program->levelLabel() }} · {{ $program->category }}
        @if($program->duration_weeks) · {{ $program->duration_weeks }} minggu @endif
      </p>
    </div>
  </div>

  <div class="flex gap-2">
    <form method="POST" action="{{ route('admin.programs.toggle', $program) }}">
      @csrf @method('PATCH')
      <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold transition-all duration-200 border-none cursor-pointer
                     {{ $program->isPublished() ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-primary text-white hover:bg-primary-dark' }}">
        {{ $program->isPublished() ? 'Arsipkan (Kembalikan ke Draf)' : 'Publikasikan' }}
      </button>
    </form>
    <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" onsubmit="return confirm('Hapus program ini secara permanen?');">
      @csrf @method('DELETE')
      <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-[13px] font-semibold
                     bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 border-none cursor-pointer">
        Hapus Program
      </button>
    </form>
  </div>
</div>

@if($program->description)
  <div class="bg-white border border-slate-200 rounded-xl p-5 mb-6 text-sm text-slate-600 leading-relaxed">
    {{ $program->description }}
  </div>
@endif

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">{{ $performance['enrolled_count'] }}</div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Member Mengikuti</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">
      {{ $performance['average_progress'] !== null ? number_format($performance['average_progress'], 1) . '%' : '—' }}
    </div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Rata-rata Progres</div>
  </div>
  <div class="bg-white border border-slate-200 rounded-xl p-5">
    <div class="text-[24px] font-extrabold text-slate-900">
      {{ $performance['completion_rate'] !== null ? number_format($performance['completion_rate'], 1) . '%' : '—' }}
    </div>
    <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Tingkat Penyelesaian</div>
  </div>
</div>

{{-- Exercises list --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
  <div class="px-5 py-4 border-b border-slate-100">
    <div class="text-[14px] font-bold text-slate-900">Daftar Latihan</div>
    <div class="text-[11px] text-slate-400 mt-0.5">{{ $program->exercises->count() }} jenis latihan dalam program ini</div>
  </div>

  @if($program->exercises->isEmpty())
    <div class="px-5 py-10 text-center text-[13px] text-slate-400">Belum ada latihan yang ditambahkan ke program ini.</div>
  @else
    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-slate-50">
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Latihan</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Set × Reps</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Istirahat</th>
        </tr>
      </thead>
      <tbody>
        @foreach($program->exercises as $exercise)
          <tr class="border-b border-slate-100 last:border-b-0">
            <td class="px-4 py-3 text-[13px] font-semibold text-slate-900">{{ $exercise->name }}</td>
            <td class="px-4 py-3 text-[12px] text-slate-500">
              {{ $exercise->sets }} set × {{ $exercise->reps ?? $exercise->duration_seconds . 's' }}
            </td>
            <td class="px-4 py-3 text-[12px] text-slate-500">{{ $exercise->rest_seconds }}d</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@endsection
