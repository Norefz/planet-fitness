@extends('layouts.mentor')
@section('title', 'Edit ' . $program->title)

@section('content')

  <div class="mb-8">
    <a href="{{ route('mentor.programs.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors mb-5">
      <x-mentor.icon name="arrow-left" class="w-4 h-4" /> Kembali ke Program Latihan
    </a>
    <div class="flex items-center gap-3 flex-wrap">
      <div>
        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">
          <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span> Edit Program
        </div>
        <h1 class="display-heading text-[28px] sm:text-3xl font-extrabold text-slate-900">{{ $program->title }}</h1>
      </div>
      <x-mentor.badge :variant="$program->isPublished() ? 'success' : 'neutral'" :dot="true">
        {{ $program->isPublished() ? 'Dipublikasikan' : 'Draf' }}
      </x-mentor.badge>
    </div>
    <p class="text-sm text-slate-500 mt-1.5">Terakhir diperbarui {{ $program->updated_at->diffForHumans() }} · <a href="{{ route('mentor.programs.show', $program) }}" class="text-primary-600 font-semibold hover:text-primary-700">Lihat progres member →</a></p>
  </div>

  <form method="POST" action="{{ route('mentor.programs.update', $program) }}">
    @method('PUT')
    @include('mentor.programs._form')
  </form>

  @include('mentor.programs._exercises-section')

@endsection
