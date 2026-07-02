@extends('layouts.mentor')
@section('title', 'Buat Program Baru')

@section('content')

  <div class="mb-8">
    <a href="{{ route('mentor.programs.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 transition mb-4">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Kembali ke Program Latihan
    </a>
    <div class="text-xs font-bold text-primary-dark tracking-widest uppercase mb-2">Program Baru</div>
    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Buat Program Latihan</h1>
    <p class="text-sm text-slate-500 mt-1.5">Susun detail programmu di bawah ini, lalu publikasikan atau simpan sebagai draf.</p>
  </div>

  <form method="POST" action="{{ route('mentor.programs.store') }}">
    @include('mentor.programs._form')
  </form>

@endsection
