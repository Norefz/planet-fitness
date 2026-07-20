@extends('admin.layouts.app')

@section('title', 'Pencarian')
@section('page_title', 'Pencarian')
@section('page_subtitle', 'Member, Mentor, Program, dan Booking')

@section('content')
  <form method="GET" action="{{ route('admin.search') }}" class="flex gap-2 mb-6 max-w-2xl">
    <input name="q" value="{{ $q }}" autofocus placeholder="Cari member, mentor, program, atau booking..."
           class="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 text-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/15" />
    <button type="submit" class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold">Cari</button>
  </form>

  @if($q === '')
    <div class="rounded-xl border border-slate-200 bg-white px-5 py-10 text-center text-sm text-slate-400">
      Masukkan kata kunci untuk mencari data di panel admin.
    </div>
  @else
    <p class="text-sm text-slate-500 mb-5">{{ $members->count() + $mentors->count() + $programs->count() + $bookings->count() }} hasil untuk <strong class="text-slate-700">“{{ $q }}”</strong></p>

    <div class="grid gap-4 lg:grid-cols-2">
      @foreach([
        ['title' => 'Member', 'items' => $members, 'url' => fn ($item) => route('admin.members.show', $item), 'detail' => fn ($item) => $item->user?->email ?? '-'],
        ['title' => 'Mentor', 'items' => $mentors, 'url' => fn ($item) => route('admin.mentors.show', $item), 'detail' => fn ($item) => $item->specialization ?: ($item->user?->email ?? '-')],
        ['title' => 'Program Latihan', 'items' => $programs, 'url' => fn ($item) => route('admin.programs.show', $item), 'detail' => fn ($item) => ($item->mentor?->full_name ?? 'Tanpa mentor') . ' · ' . $item->category],
        ['title' => 'Booking', 'items' => $bookings, 'url' => fn ($item) => route('admin.bookings', ['q' => $item->member?->full_name]), 'detail' => fn ($item) => ($item->member?->full_name ?? '-') . ' × ' . ($item->mentor?->full_name ?? '-')],
      ] as $section)
        <section class="rounded-xl border border-slate-200 bg-white overflow-hidden">
          <div class="px-5 py-3.5 border-b border-slate-100 font-bold text-sm text-slate-800">{{ $section['title'] }}</div>
          @forelse($section['items'] as $item)
            <a href="{{ $section['url']($item) }}" class="block px-5 py-3 border-b border-slate-100 last:border-0 hover:bg-slate-50 no-underline">
              <div class="font-semibold text-sm text-slate-800">{{ $item->full_name ?? $item->title ?? ($item->topic ?: 'Konsultasi') }}</div>
              <div class="text-xs text-slate-400 mt-0.5">{{ $section['detail']($item) }}</div>
            </a>
          @empty
            <div class="px-5 py-5 text-sm text-slate-400">Tidak ada hasil.</div>
          @endforelse
        </section>
      @endforeach
    </div>
  @endif
@endsection
