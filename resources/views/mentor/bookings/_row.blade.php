@php
    $statusVariant = match ($booking->status) {
        'pending'   => 'warning',
        'confirmed' => 'success',
        'completed' => 'info',
        'cancelled' => 'neutral',
    };
    $avatarTone = match ($booking->status) {
        'pending'   => 'amber',
        'confirmed' => 'primary',
        default     => 'neutral',
    };
@endphp

<x-mentor.card padding="p-5" class="flex items-center gap-4 flex-wrap">

  <x-mentor.avatar :name="$booking->member->full_name ?? 'Member'" :tone="$avatarTone" size="lg" />

  <div class="flex-1 min-w-[200px]">
    <div class="text-sm font-bold text-slate-900">{{ $booking->member->full_name ?? 'Member' }}</div>
    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 flex-wrap">
      <x-mentor.icon name="calendar" class="w-3.5 h-3.5" />
      {{ $booking->scheduled_at->translatedFormat('l, d M Y') }} · {{ $booking->scheduled_at->format('H:i') }} WIB
      @if ($booking->topic)
        <span class="text-slate-300">·</span> {{ $booking->topic }}
      @endif
    </div>
    <x-mentor.badge :variant="$statusVariant" class="mt-2.5">{{ $booking->statusLabel() }}</x-mentor.badge>
  </div>

  <div class="flex items-center gap-2 flex-wrap">
    @if ($context === 'pending')
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="confirm">
        <x-mentor.button type="submit" size="sm">
          <x-mentor.icon name="check" class="w-3.5 h-3.5" /> Konfirmasi
        </x-mentor.button>
      </form>
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" onsubmit="return confirm('Tolak permintaan booking ini?');">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="cancel">
        <x-mentor.button type="submit" size="sm" variant="danger">
          <x-mentor.icon name="x" class="w-3.5 h-3.5" /> Tolak
        </x-mentor.button>
      </form>
    @endif

    @if ($context === 'confirmed')
      @if ($booking->meeting_url)
        <x-mentor.button :href="$booking->meeting_url" size="sm" target="_blank" rel="noopener">
          <x-mentor.icon name="video" class="w-3.5 h-3.5" /> Mulai Sesi
        </x-mentor.button>
      @endif
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="complete">
        <x-mentor.button type="submit" size="sm" variant="secondary">Tandai Selesai</x-mentor.button>
      </form>
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" onsubmit="return confirm('Batalkan sesi ini?');">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="cancel">
        <x-mentor.button type="submit" size="sm" variant="ghost">Batalkan</x-mentor.button>
      </form>
    @endif

    @if ($context === 'history' && $booking->status === 'completed')
      <x-mentor.button type="button" size="sm" variant="secondary" onclick="document.getElementById('notes-{{ $booking->id }}').showModal()">
        <x-mentor.icon name="file-text" class="w-3.5 h-3.5" />
        {{ $booking->mentor_notes ? 'Lihat Catatan' : 'Tambah Catatan' }}
      </x-mentor.button>

      <dialog id="notes-{{ $booking->id }}" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
        <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" class="p-6">
          @csrf @method('PATCH')
          <input type="hidden" name="action" value="notes">
          <h4 class="text-base font-bold text-slate-900 mb-1">Catatan Sesi</h4>
          <p class="text-xs text-slate-500 mb-4">Ringkas hasil konsultasi dengan {{ $booking->member->full_name ?? 'member' }} untuk referensi berikutnya.</p>
          <x-mentor.textarea name="mentor_notes" :rows="4" maxlength="2000" placeholder="mis. Fokus perbaikan form squat, evaluasi ulang 2 minggu lagi...">{{ $booking->mentor_notes }}</x-mentor.textarea>
          <div class="flex justify-end gap-2.5 mt-5">
            <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('notes-{{ $booking->id }}').close()">Tutup</x-mentor.button>
            <x-mentor.button type="submit">Simpan Catatan</x-mentor.button>
          </div>
        </form>
      </dialog>
    @endif
  </div>
</x-mentor.card>
