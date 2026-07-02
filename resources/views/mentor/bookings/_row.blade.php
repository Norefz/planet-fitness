@php
    $initials = collect(explode(' ', $booking->member->full_name ?? 'Member'))
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->take(2)->implode('');

    $statusStyles = [
        'pending'   => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'clock'],
        'confirmed' => ['bg' => '#e1f5ee', 'text' => '#0f6e56', 'icon' => 'check'],
        'completed' => ['bg' => '#e6f1fb', 'text' => '#185fa5', 'icon' => 'check'],
        'cancelled' => ['bg' => '#f1f5f9', 'text' => '#64748b', 'icon' => 'x'],
    ][$booking->status];
@endphp

<div class="bg-white border border-slate-200 rounded-2xl px-6 py-5 flex items-center gap-4 flex-wrap">

  <div class="w-11 h-11 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0">
    {{ $initials }}
  </div>

  <div class="flex-1 min-w-[200px]">
    <div class="text-sm font-bold">{{ $booking->member->full_name ?? 'Member' }}</div>
    <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5 flex-wrap">
      @include('mentor.partials.icon', ['name' => 'calendar', 'class' => 'w-3.5 h-3.5'])
      {{ $booking->scheduled_at->translatedFormat('l, d M Y') }} · {{ $booking->scheduled_at->format('H:i') }} WIB
      @if ($booking->topic)
        <span class="text-slate-300">·</span> {{ $booking->topic }}
      @endif
    </div>
    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full mt-2"
          style="background:{{ $statusStyles['bg'] }}; color:{{ $statusStyles['text'] }};">
      {{ $booking->statusLabel() }}
    </span>
  </div>

  <div class="flex items-center gap-2 flex-wrap">
    @if ($context === 'pending')
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="confirm">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-dark text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition">
          @include('mentor.partials.icon', ['name' => 'check', 'class' => 'w-3.5 h-3.5']) Konfirmasi
        </button>
      </form>
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" onsubmit="return confirm('Tolak permintaan booking ini?');">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="cancel">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-3.5 py-2 rounded-lg transition">
          @include('mentor.partials.icon', ['name' => 'x', 'class' => 'w-3.5 h-3.5']) Tolak
        </button>
      </form>
    @endif

    @if ($context === 'confirmed')
      @if ($booking->meeting_url)
        <a href="{{ $booking->meeting_url }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-dark text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition">
          @include('mentor.partials.icon', ['name' => 'video', 'class' => 'w-3.5 h-3.5']) Mulai Sesi
        </a>
      @endif
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="complete">
        <button type="submit" class="inline-flex items-center gap-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3.5 py-2 rounded-lg transition">
          Tandai Selesai
        </button>
      </form>
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" onsubmit="return confirm('Batalkan sesi ini?');">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="cancel">
        <button type="submit" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-red-600 text-xs font-semibold px-2 py-2 transition">
          Batalkan
        </button>
      </form>
    @endif

    @if ($context === 'history' && $booking->status === 'completed')
      <button type="button" onclick="document.getElementById('notes-{{ $booking->id }}').showModal()"
              class="inline-flex items-center gap-1.5 border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3.5 py-2 rounded-lg transition">
        @include('mentor.partials.icon', ['name' => 'file-text', 'class' => 'w-3.5 h-3.5'])
        {{ $booking->mentor_notes ? 'Lihat Catatan' : 'Tambah Catatan' }}
      </button>

      <dialog id="notes-{{ $booking->id }}" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-slate-900/40">
        <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" class="p-6">
          @csrf @method('PATCH')
          <input type="hidden" name="action" value="notes">
          <h4 class="text-base font-bold mb-1">Catatan Sesi</h4>
          <p class="text-xs text-slate-500 mb-4">Ringkas hasil konsultasi dengan {{ $booking->member->full_name ?? 'member' }} untuk referensi berikutnya.</p>
          <textarea name="mentor_notes" rows="4" maxlength="2000" placeholder="mis. Fokus perbaikan form squat, evaluasi ulang 2 minggu lagi..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition resize-none mb-5">{{ $booking->mentor_notes }}</textarea>
          <div class="flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('notes-{{ $booking->id }}').close()"
                    class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Tutup</button>
            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold bg-primary hover:bg-primary-dark text-white transition">Simpan Catatan</button>
          </div>
        </form>
      </dialog>
    @endif
  </div>
</div>
