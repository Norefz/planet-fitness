@extends('layouts.mentor')
@section('title', 'Konsultasi')

@section('content')

  <div class="mb-8">
    <div class="text-xs font-bold text-primary-dark tracking-widest uppercase mb-2">Penjadwalan & Interaksi</div>
    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Konsultasi Mentor</h1>
    <p class="text-sm text-slate-500 mt-1.5 max-w-lg">Tinjau permintaan booking yang masuk dan kelola jadwal sesi konsultasimu dengan member.</p>
  </div>

  {{-- Pending requests --}}
  <div class="mb-10">
    <h3 class="text-base font-bold mb-4 flex items-center gap-2">
      Permintaan Booking Masuk
      @if ($pending->count())
        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full" style="background:#fef3c7; color:#92400e;">{{ $pending->count() }}</span>
      @endif
    </h3>

    @if ($pending->isEmpty())
      <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-8 text-center">
        <p class="text-sm text-slate-500">Tidak ada permintaan booking yang menunggu konfirmasi.</p>
      </div>
    @else
      <div class="flex flex-col gap-3">
        @foreach ($pending as $booking)
          @include('mentor.bookings._row', ['booking' => $booking, 'context' => 'pending'])
        @endforeach
      </div>
    @endif
  </div>

  {{-- Confirmed / upcoming --}}
  <div class="mb-10">
    <h3 class="text-base font-bold mb-4">Jadwal Terkonfirmasi</h3>

    @if ($confirmed->isEmpty())
      <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-8 text-center">
        <p class="text-sm text-slate-500">Belum ada sesi terkonfirmasi mendatang.</p>
      </div>
    @else
      <div class="flex flex-col gap-3">
        @foreach ($confirmed as $booking)
          @include('mentor.bookings._row', ['booking' => $booking, 'context' => 'confirmed'])
        @endforeach
      </div>
    @endif
  </div>

  {{-- History --}}
  <div>
    <h3 class="text-base font-bold mb-4">Riwayat Sesi</h3>

    @if ($history->isEmpty())
      <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-8 text-center">
        <p class="text-sm text-slate-500">Belum ada riwayat sesi selesai atau dibatalkan.</p>
      </div>
    @else
      <div class="flex flex-col gap-3">
        @foreach ($history as $booking)
          @include('mentor.bookings._row', ['booking' => $booking, 'context' => 'history'])
        @endforeach
      </div>
    @endif
  </div>

@endsection
