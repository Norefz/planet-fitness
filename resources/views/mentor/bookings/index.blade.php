@extends('layouts.mentor')
@section('title', 'Konsultasi')

@section('content')

  <div class="mb-8">
    <div class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">
      <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span> Penjadwalan & Interaksi
    </div>
    <h1 class="display-heading text-[28px] sm:text-3xl font-extrabold text-slate-900">Konsultasi <span class="text-gradient">Mentor</span></h1>
    <p class="text-sm text-slate-500 mt-1.5 max-w-lg">Tinjau permintaan booking yang masuk dan kelola jadwal sesi konsultasimu dengan member.</p>
  </div>

  <div class="mb-10">
    <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
      Permintaan Booking Masuk
      @if ($pending->count())
        <x-mentor.badge variant="warning">{{ $pending->count() }}</x-mentor.badge>
      @endif
    </h3>

    @if ($pending->isEmpty())
      <x-mentor.empty-state icon="inbox" title="Tidak ada permintaan baru" description="Permintaan booking dari member akan muncul di sini." />
    @else
      <div class="flex flex-col gap-3">
        @foreach ($pending as $booking)
          @include('mentor.bookings._row', ['booking' => $booking, 'context' => 'pending'])
        @endforeach
      </div>
    @endif
  </div>

  <div class="mb-10">
    <h3 class="text-base font-bold text-slate-900 mb-4">Jadwal Terkonfirmasi</h3>

    @if ($confirmed->isEmpty())
      <x-mentor.empty-state icon="calendar" title="Belum ada sesi terkonfirmasi" description="Sesi yang sudah dikonfirmasi akan tampil di sini lengkap dengan tautan Zoom-nya." />
    @else
      <div class="flex flex-col gap-3">
        @foreach ($confirmed as $booking)
          @include('mentor.bookings._row', ['booking' => $booking, 'context' => 'confirmed'])
        @endforeach
      </div>
    @endif
  </div>

  <div>
    <h3 class="text-base font-bold text-slate-900 mb-4">Riwayat Sesi</h3>

    @if ($history->isEmpty())
      <x-mentor.empty-state icon="clock" title="Belum ada riwayat" description="Sesi yang sudah selesai atau dibatalkan akan muncul di sini." />
    @else
      <div class="flex flex-col gap-3">
        @foreach ($history as $booking)
          @include('mentor.bookings._row', ['booking' => $booking, 'context' => 'history'])
        @endforeach
      </div>
    @endif
  </div>

@endsection
