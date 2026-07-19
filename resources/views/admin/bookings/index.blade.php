@extends('admin.layouts.app')

@section('title', 'Booking Konsultasi')
@section('page_title', 'Booking Konsultasi')
@section('page_subtitle', 'Kelola Semua Booking')

@section('content')

{{-- ══════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-[22px] font-extrabold tracking-tight text-slate-900">Booking Konsultasi</h1>
    <p class="text-[13px] text-slate-500 mt-0.5">
      {{ $stats['pending'] }} menunggu konfirmasi · {{ $stats['today'] }} sesi hari ini
    </p>
  </div>
</div>

{{-- ══════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════ --}}
<div class="grid grid-cols-4 gap-4 mb-6">

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#f59e0b;">
    <div class="w-10 h-10 rounded-[10px] bg-amber-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $stats['pending'] }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Pending Konfirmasi</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Perlu tindakan segera</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#1d9e75;">
    <div class="w-10 h-10 rounded-[10px] bg-primary-light flex items-center justify-center">
      <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $stats['confirmed_this_week'] }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Dikonfirmasi</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Minggu ini</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#3b82f6;">
    <div class="w-10 h-10 rounded-[10px] bg-blue-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $stats['today'] }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Sesi Hari Ini</div>
    </div>
  </div>

  <div class="bg-white border border-slate-200 rounded-xl p-5 flex flex-col gap-3.5 shadow-[0_1px_3px_rgb(0_0_0/.08)] relative overflow-hidden stat-bar" style="--bar-color:#ef4444;">
    <div class="w-10 h-10 rounded-[10px] bg-red-50 flex items-center justify-center">
      <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
      </svg>
    </div>
    <div>
      <div class="text-[28px] font-extrabold tracking-tight text-slate-900">{{ $stats['cancelled_this_month'] }}</div>
      <div class="text-[12px] font-semibold text-slate-500 mt-0.5">Dibatalkan</div>
      <div class="text-[11px] text-slate-400 mt-0.5">Bulan ini</div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-4">

  {{-- ══════════════════════════════════════════
       BOOKING TABLE
  ══════════════════════════════════════════ --}}
  <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
    <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-slate-100 flex-wrap">
      <div>
        <div class="text-[14px] font-bold text-slate-900">Semua Booking</div>
        @if($date)
          @php
            $bulanIndoFilter = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            $selectedDateObj = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
          @endphp
          <div class="flex items-center gap-2 mt-0.5">
            <span class="text-[11px] text-slate-500">
              Menampilkan booking pada <strong class="text-slate-700">{{ $selectedDateObj->day }} {{ $bulanIndoFilter[$selectedDateObj->month - 1] }} {{ $selectedDateObj->year }}</strong>
            </span>
            <a href="{{ route('admin.bookings', array_filter(['status' => $status, 'q' => $q, 'month' => $calendarMonth->format('Y-m')])) }}"
               class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary hover:text-primary-dark no-underline">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              Hapus filter tanggal
            </a>
          </div>
        @else
          <div class="text-[11px] text-slate-400 mt-0.5">Status mengikuti konfirmasi mentor, bukan pembayaran</div>
        @endif
      </div>

      <form method="GET" action="{{ route('admin.bookings') }}" class="flex items-center gap-2">
        @if($date)
          <input type="hidden" name="date" value="{{ $date }}" />
        @endif
        <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 cursor-pointer">
          <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
          <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
          <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
          <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari member/mentor..."
               class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs text-slate-700 outline-none w-40" />
      </form>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-slate-50">
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Member</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Mentor</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Waktu Sesi</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Status</th>
          <th class="text-left px-4 py-2.5 text-[11px] font-bold text-slate-500 uppercase tracking-wide border-b border-slate-100">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bookings as $booking)
          @php
            $badgeMap = [
              'pending'   => ['Pending', 'bg-amber-100 text-amber-800'],
              'confirmed' => ['Dikonfirmasi', 'bg-primary-light text-primary-dark'],
              'completed' => ['Selesai', 'bg-slate-100 text-slate-500'],
              'cancelled' => ['Dibatalkan', 'bg-red-50 text-red-600'],
            ];
            [$badgeLabel, $badgeClass] = $badgeMap[$booking->status] ?? ['—', 'bg-slate-100 text-slate-500'];
          @endphp
          <tr class="border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors {{ $booking->status === 'pending' ? 'bg-amber-50/40' : '' }} {{ $booking->status === 'cancelled' ? 'opacity-60' : '' }}">
            <td class="px-4 py-3">
              <div class="flex items-center gap-2.5">
                @if($booking->member->profile_photo_url)
                  <img src="{{ $booking->member->profile_photo_url }}" alt="{{ $booking->member->full_name }}"
                       class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                @else
                  <div class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-bold text-white flex-shrink-0"
                       style="background: linear-gradient(135deg, #1d9e75, #0f6e56);">
                    {{ $booking->member->initials() }}
                  </div>
                @endif
                <div class="min-w-0">
                  <div class="text-[13px] font-semibold text-slate-900 truncate">{{ $booking->member->full_name }}</div>
                  <div class="text-[11px] text-slate-400 truncate">{{ $booking->member->user?->email }}</div>
                </div>
              </div>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                @if($booking->mentor->profile_photo_url)
                  <img src="{{ $booking->mentor->profile_photo_url }}" alt="{{ $booking->mentor->full_name }}"
                       class="w-6 h-6 rounded-full object-cover flex-shrink-0" />
                @else
                  <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0"
                       style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    {{ $booking->mentor->initials() }}
                  </div>
                @endif
                <span class="text-[12px] text-slate-700">{{ $booking->mentor->full_name }}</span>
              </div>
            </td>
            <td class="px-4 py-3 text-[12px] text-slate-500">
              <div class="flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $booking->scheduled_at->format('d/m · H:i') }} – {{ $booking->scheduled_at->copy()->addMinutes($booking->duration_minutes)->format('H:i') }}
              </div>
            </td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-1.5">
                @if($booking->status === 'pending')
                  <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}">
                    @csrf @method('PATCH')
                    <button type="submit" title="Konfirmasi"
                            class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-primary hover:bg-primary-light transition-all duration-200 cursor-pointer">
                      <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    </button>
                  </form>
                @endif

                @if(in_array($booking->status, ['pending', 'confirmed']))
                  <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}"
                        onsubmit="return setCancelReason(this);">
                    @csrf @method('PATCH')
                    <input type="hidden" name="cancellation_reason" />
                    <button type="submit" title="Batalkan"
                            class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-red-500 hover:bg-red-50 transition-all duration-200 cursor-pointer">
                      <svg class="w-[13px] h-[13px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-10 text-center text-[13px] text-slate-400">
              @if($date)
                Tidak ada booking pada tanggal yang dipilih.
              @else
                Tidak ada booking yang cocok dengan pencarian/filter ini.
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($bookings->hasPages())
      <div class="px-5 py-4 border-t border-slate-100">
        {{ $bookings->links() }}
      </div>
    @endif
  </div>

  {{-- ══════════════════════════════════════════
       MINI CALENDAR + TODAY'S SCHEDULE
  ══════════════════════════════════════════ --}}
  <div class="flex flex-col gap-4">

    <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
        <div class="text-[13px] font-bold text-slate-900">
          @php
            $bulanIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
          @endphp
          {{ $bulanIndo[$calendarMonth->month - 1] }} {{ $calendarMonth->year }}
        </div>
        <div class="flex gap-1">
          <a href="{{ route('admin.bookings', ['month' => $calendarMonth->copy()->subMonth()->format('Y-m')]) }}"
             class="w-6 h-6 rounded-md border border-slate-200 bg-white flex items-center justify-center text-slate-500 no-underline">‹</a>
          <a href="{{ route('admin.bookings', ['month' => $calendarMonth->copy()->addMonth()->format('Y-m')]) }}"
             class="w-6 h-6 rounded-md border border-slate-200 bg-white flex items-center justify-center text-slate-500 no-underline">›</a>
        </div>
      </div>

      <div class="grid grid-cols-7 gap-1 p-3 text-center">
        @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $d)
          <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $d }}</div>
        @endforeach

        @php
          $firstDay = $calendarMonth->copy()->startOfMonth();
          $leadingBlanks = $firstDay->dayOfWeekIso - 1; // Senin=1 -> 0 blanks
          $daysInMonth = $calendarMonth->daysInMonth;
        @endphp

        @for($i = 0; $i < $leadingBlanks; $i++)
          <div></div>
        @endfor

        @for($day = 1; $day <= $daysInMonth; $day++)
          @php
            $dateStr = $calendarMonth->copy()->day($day)->format('Y-m-d');
            $isToday = $dateStr === today()->format('Y-m-d');
            $isSelected = $date === $dateStr;
            $hasBooking = isset($bookingDates[$dateStr]);
          @endphp
          <a href="{{ route('admin.bookings', array_filter(['status' => $status, 'q' => $q, 'month' => $calendarMonth->format('Y-m'), 'date' => $isSelected ? null : $dateStr])) }}"
             title="{{ $hasBooking ? 'Lihat booking tanggal ini' : 'Tidak ada booking pada tanggal ini' }}"
             class="aspect-square flex items-center justify-center text-[11px] rounded-lg relative no-underline cursor-pointer transition-colors duration-150
                        {{ $isSelected ? 'bg-primary text-white font-bold ring-2 ring-primary ring-offset-1' : ($isToday ? 'bg-primary-light text-primary-dark font-bold' : 'text-slate-700 hover:bg-slate-100') }}">
            {{ $day }}
            @if($hasBooking && !$isSelected)
              <span class="absolute bottom-0.5 w-1 h-1 rounded-full {{ $isToday ? 'bg-primary-dark' : 'bg-amber-500' }}"></span>
            @endif
          </a>
        @endfor
      </div>

      <div class="flex items-center gap-3 px-4 pb-3 text-[11px] text-slate-400 flex-wrap">
        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Ada booking</span>
        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary-light border border-primary"></span> Hari ini</span>
        @if($date)
          <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-primary"></span> Dipilih</span>
        @endif
      </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-[0_1px_3px_rgb(0_0_0/.08)] overflow-hidden">
      <div class="px-4 py-3 border-b border-slate-100">
        <div class="text-[13px] font-bold text-slate-900">Jadwal Hari Ini</div>
        <div class="text-[11px] text-slate-400 mt-0.5">{{ $todaySchedule->count() }} sesi tersisa</div>
      </div>
      <div class="flex flex-col gap-2 p-3">
        @forelse($todaySchedule as $item)
          <div class="flex items-center gap-2.5 p-2.5 rounded-lg {{ $item->status === 'pending' ? 'bg-amber-50 border-l-2 border-amber-500' : 'bg-slate-50 border-l-2 border-primary' }}">
            <div class="text-[11px] font-bold {{ $item->status === 'pending' ? 'text-amber-700' : 'text-slate-500' }} whitespace-nowrap">
              {{ $item->scheduled_at->format('H:i') }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-[12px] font-semibold text-slate-900 truncate">{{ $item->member->full_name }} × {{ $item->mentor->full_name }}</div>
              <div class="text-[11px] text-slate-400">{{ $item->duration_minutes }} mnt</div>
            </div>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $item->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-primary-light text-primary-dark' }}">
              {{ $item->status === 'pending' ? 'Pending' : 'OK' }}
            </span>
          </div>
        @empty
          <div class="text-center text-[12px] text-slate-400 py-6">Tidak ada sesi hari ini.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<script>
  function setCancelReason(form) {
    const reason = prompt('Alasan pembatalan (opsional):', '');
    if (reason === null) return false; // batal
    form.querySelector('input[name="cancellation_reason"]').value = reason;
    return confirm('Batalkan booking ini?');
  }
</script>

@endsection
