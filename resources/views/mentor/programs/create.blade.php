@extends('layouts.mentor')
@section('title', 'Buat Program Baru')

@section('content')

  <div class="mb-8">
    <a href="{{ route('mentor.programs.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors mb-5">
      <x-mentor.icon name="arrow-left" class="w-4 h-4" /> Kembali ke Program Latihan
    </a>
    <div class="text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">Program Baru</div>
    <h1 class="text-[28px] sm:text-3xl font-bold tracking-tight text-slate-900">Buat Program Latihan</h1>
    <p class="text-sm text-slate-500 mt-1.5">Susun detail programmu di bawah ini, lalu publikasikan atau simpan sebagai draf.</p>
  </div>

  <form method="POST" action="{{ route('mentor.programs.store') }}">
    @include('mentor.programs._form')
  </form>

@endsection
