@extends('layouts.mentor')
@section('title', 'Dashboard')

@section('content')

  <div class="flex items-end justify-between flex-wrap gap-4 mb-8">
    <div>
      <div class="text-xs font-bold text-primary-dark tracking-widest uppercase mb-2">Ringkasan</div>
      <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Halo, {{ $mentor->full_name }} 👋</h1>
      <p class="text-sm text-slate-500 mt-1.5">Ini yang terjadi di programmu hari ini.</p>
    </div>
    <a href="{{ route('mentor.programs.create') }}"
       class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm hover:-translate-y-px transition-all">
      @include('mentor.partials.icon', ['name' => 'plus', 'class' => 'w-4 h-4'])
      Buat Program Baru
    </a>
  </div>

  {{-- Stat cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <div class="text-xs text-slate-500 mb-1.5">Total Program</div>
      <div class="text-2xl font-bold">{{ $stats['programs_total'] }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <div class="text-xs text-slate-500 mb-1.5">Dipublikasikan</div>
      <div class="text-2xl font-bold text-primary">{{ $stats['programs_published'] }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <div class="text-xs text-slate-500 mb-1.5">Draf</div>
      <div class="text-2xl font-bold text-slate-400">{{ $stats['programs_draft'] }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5">
      <div class="text-xs text-slate-500 mb-1.5">Menunggu Konfirmasi</div>
      <div class="text-2xl font-bold" style="color:#92400e;">{{ $stats['bookings_pending'] }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5 col-span-2 lg:col-span-1">
      <div class="text-xs text-slate-500 mb-1.5">Sesi Terjadwal</div>
      <div class="text-2xl font-bold">{{ $stats['bookings_upcoming'] }}</div>
    </div>
  </div>

  <div class="grid lg:grid-cols-2 gap-8">

    {{-- Recent programs --}}
    <div>
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold">Program Terbaru</h3>
        <a href="{{ route('mentor.programs.index') }}" class="text-sm font-semibold text-primary-dark hover:underline flex items-center gap-1">
          Lihat semua @include('mentor.partials.icon', ['name' => 'chevron-right', 'class' => 'w-3.5 h-3.5'])
        </a>
      </div>

      @if ($recentPrograms->isEmpty())
        <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-8 text-center">
          <p class="text-sm text-slate-500 mb-4">Kamu belum punya program latihan.</p>
          <a href="{{ route('mentor.programs.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary-dark hover:underline">
            @include('mentor.partials.icon', ['name' => 'plus', 'class' => 'w-4 h-4']) Buat program pertamamu
          </a>
        </div>
      @else
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-100">
          @foreach ($recentPrograms as $program)
            @php $theme = $program->themeColor(); @endphp
            <a href="{{ route('mentor.programs.edit', $program) }}" class="flex items-center gap-3.5 px-5 py-4 hover:bg-slate-50 transition">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, {{ $theme['from'] }}, {{ $theme['to'] }});">
                @include('mentor.partials.icon', ['name' => $theme['icon'], 'class' => 'w-4.5 h-4.5 text-white'])
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate">{{ $program->title }}</div>
                <div class="text-xs text-slate-500">{{ $program->category }} · {{ $program->levelLabel() }}</div>
              </div>
              <span class="text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0 {{ $program->isPublished() ? 'bg-primary-light text-primary-dark' : 'bg-slate-100 text-slate-500' }}">
                {{ $program->isPublished() ? 'Live' : 'Draf' }}
              </span>
            </a>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Pending bookings --}}
    <div>
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold">Permintaan Booking Masuk</h3>
        <a href="{{ route('mentor.bookings.index') }}" class="text-sm font-semibold text-primary-dark hover:underline flex items-center gap-1">
          Lihat semua @include('mentor.partials.icon', ['name' => 'chevron-right', 'class' => 'w-3.5 h-3.5'])
        </a>
      </div>

      @if ($pendingBookings->isEmpty())
        <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-8 text-center">
          @include('mentor.partials.icon', ['name' => 'inbox', 'class' => 'w-6 h-6 text-slate-300 mx-auto mb-3'])
          <p class="text-sm text-slate-500">Belum ada permintaan booking baru.</p>
        </div>
      @else
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-100">
          @foreach ($pendingBookings as $booking)
            <div class="flex items-center gap-3.5 px-5 py-4">
              <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center text-xs font-bold shrink-0">
                {{ Str::of($booking->member->full_name ?? '?')->explode(' ')->map(fn($w) => mb_substr($w,0,1))->take(2)->implode('') }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold truncate">{{ $booking->member->full_name ?? 'Member' }}</div>
                <div class="text-xs text-slate-500">{{ $booking->scheduled_at->translatedFormat('d M Y · H:i') }} WIB</div>
              </div>
              <span class="text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0" style="background:#fef3c7; color:#92400e;">Pending</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

@endsection
