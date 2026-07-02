@extends('layouts.mentor')
@section('title', 'Edit ' . $program->title)

@section('content')

  <div class="mb-8">
    <a href="{{ route('mentor.programs.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 transition mb-4">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Kembali ke Program Latihan
    </a>
    <div class="flex items-center gap-3 flex-wrap">
      <div>
        <div class="text-xs font-bold text-primary-dark tracking-widest uppercase mb-2">Edit Program</div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">{{ $program->title }}</h1>
      </div>
      <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $program->isPublished() ? 'bg-primary-light text-primary-dark' : 'bg-slate-100 text-slate-500' }}">
        {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
      </span>
    </div>
    <p class="text-sm text-slate-500 mt-1.5">Terakhir diperbarui {{ $program->updated_at->diffForHumans() }}.</p>
  </div>

  <form method="POST" action="{{ route('mentor.programs.update', $program) }}">
    @method('PUT')
    @include('mentor.programs._form')
  </form>

@endsection
