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

<x-mentor.card padding="p-5" hover class="flex items-center gap-4 flex-wrap">

  <x-mentor.avatar :name="$booking->member->full_name ?? 'Member'" :tone="$avatarTone" size="lg" ring />

  <div class="flex-1 min-w-[200px]">
    <div class="text-sm font-bold text-slate-900">{{ $booking->member->full_name ?? 'Member' }}</div>
    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 flex-wrap">
      <x-mentor.icon name="calendar" class="w-3.5 h-3.5" />
      {{ $booking->scheduled_at->translatedFormat('l, d M Y') }} · {{ $booking->scheduled_at->format('H:i') }} WIB
      @if ($booking->topic)
        <span class="text-slate-300">·</span> {{ $booking->topic }}
      @endif
    </div>
    <x-mentor.badge :variant="$statusVariant" :dot="true" class="mt-2.5">{{ $booking->statusLabel() }}</x-mentor.badge>
  </div>

  <div class="flex items-center gap-2 flex-wrap">

    {{-- ── PENDING: tombol konfirmasi & tolak ── --}}
    @if ($context === 'pending')
      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="confirm">
        <x-mentor.button type="submit" size="sm">
          <x-mentor.icon name="check" class="w-3.5 h-3.5" /> Konfirmasi
        </x-mentor.button>
      </form>

      <x-mentor.button type="button" size="sm" variant="danger" onclick="document.getElementById('reject-dialog-{{ $booking->id }}').showModal()">
        <x-mentor.icon name="x" class="w-3.5 h-3.5" /> Tolak
      </x-mentor.button>

      {{-- Dialog Modal Penolakan Sesi Pending --}}
      <dialog id="reject-dialog-{{ $booking->id }}" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
        <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" class="p-6">
          @csrf @method('PATCH')
          <input type="hidden" name="action" value="cancel">

          <h4 class="text-base font-bold text-slate-900 mb-1">Tolak Permintaan Sesi</h4>
          <p class="text-xs text-slate-500 mb-4">Berikan alasan penolakan agar member mengerti penyebab jadwal tidak disetujui.</p>

          <textarea
            name="cancellation_reason"
            rows="3"
            required
            placeholder="mis. Maaf pada jam tersebut saya ada jadwal pelatihan fisik mendadak di gym..."
            class="w-full px-3 py-2 text-sm text-slate-700 placeholder-slate-400 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
          ></textarea>

          <div class="flex justify-end gap-2.5 mt-5">
            <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('reject-dialog-{{ $booking->id }}').close()">Batal</x-mentor.button>
            <x-mentor.button type="submit" variant="danger">Kirim & Tolak</x-mentor.button>
          </div>
        </form>
      </dialog>
    @endif

    {{-- ── CONFIRMED: tombol mulai sesi, selesai, & batalkan ── --}}
    @if ($context === 'confirmed')
      @if ($booking->zoom_start_url)
        <x-mentor.button :href="$booking->zoom_start_url" size="sm" target="_blank" rel="noopener noreferrer">
          <x-mentor.icon name="video" class="w-3.5 h-3.5" /> Mulai Sesi
        </x-mentor.button>
      @elseif ($booking->meeting_url)
        <x-mentor.button :href="$booking->meeting_url" size="sm" target="_blank" rel="noopener noreferrer">
          <x-mentor.icon name="video" class="w-3.5 h-3.5" /> Mulai Sesi
        </x-mentor.button>
      @else
        <span class="text-xs text-amber-600 font-medium px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-200">
          Link Zoom belum tersedia
        </span>
      @endif

      <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="action" value="complete">
        <x-mentor.button type="submit" size="sm" variant="secondary">Tandai Selesai</x-mentor.button>
      </form>

      <x-mentor.button type="button" size="sm" variant="ghost" onclick="document.getElementById('cancel-dialog-{{ $booking->id }}').showModal()">
        Batalkan
      </x-mentor.button>

      {{-- Dialog Modal Pembatalan Sesi Terkonfirmasi --}}
      <dialog id="cancel-dialog-{{ $booking->id }}" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
        <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" class="p-6">
          @csrf @method('PATCH')
          <input type="hidden" name="action" value="cancel">

          <h4 class="text-base font-bold text-slate-900 mb-1">Batalkan Jadwal Sesi</h4>
          <p class="text-xs text-slate-500 mb-4">Membatalkan sesi ini akan menghapus meeting Zoom secara otomatis. Tulis alasan pembatalan untuk member Anda.</p>

          <textarea
            name="cancellation_reason"
            rows="3"
            required
            placeholder="mis. Terjadi kendala koneksi internet buruk atau ada urusan darurat..."
            class="w-full px-3 py-2 text-sm text-slate-700 placeholder-slate-400 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
          ></textarea>

          <div class="flex justify-end gap-2.5 mt-5">
            <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('cancel-dialog-{{ $booking->id }}').close()">Kembali</x-mentor.button>
            <x-mentor.button type="submit" variant="danger">Batalkan Sesi</x-mentor.button>
          </div>
        </form>
      </dialog>
    @endif

    {{-- ── HISTORY: catatan sesi ATAU pelacakan alasan pembatalan bagi mentor ── --}}
    @if ($context === 'history')
      @if ($booking->status === 'completed')
        <x-mentor.button type="button" size="sm" variant="secondary" onclick="document.getElementById('notes-{{ $booking->id }}').showModal()">
          <x-mentor.icon name="file-text" class="w-3.5 h-3.5" />
          {{ $booking->mentor_notes ? 'Lihat Catatan' : 'Tambah Catatan' }}
        </x-mentor.button>

        <dialog id="notes-{{ $booking->id }}" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
          <form method="POST" action="{{ route('mentor.bookings.update', $booking) }}" class="p-6">
            @csrf @method('PATCH')
            <input type="hidden" name="action" value="notes">
            <h4 class="text-base font-bold text-slate-900 mb-1">Catatan Sesi</h4>
            <p class="text-xs text-slate-500 mb-4">
              Ringkas hasil konsultasi dengan {{ $booking->member->full_name ?? 'member' }} untuk referensi berikutnya.
            </p>
            <x-mentor.textarea name="mentor_notes" :rows="4" maxlength="2000" placeholder="mis. Fokus perbaikan form squat, evaluasi ulang 2 minggu lagi...">{{ $booking->mentor_notes }}</x-mentor.textarea>
            <div class="flex justify-end gap-2.5 mt-5">
              <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('notes-{{ $booking->id }}').close()">Tutup</x-mentor.button>
              <x-mentor.button type="submit">Simpan Catatan</x-mentor.button>
            </div>
          </form>
        </dialog>
      @elseif ($booking->status === 'cancelled')
        {{-- Tombol interaktif agar mentor bisa melihat alasan batal --}}
        <x-mentor.button type="button" size="sm" variant="ghost" onclick="document.getElementById('view-reason-{{ $booking->id }}').showModal()" class="text-rose-600 hover:text-rose-700 hover:bg-rose-50/50">
          <x-mentor.icon name="alert-circle" class="w-3.5 h-3.5 mr-1" /> Lihat Alasan Batal
        </x-mentor.button>

        {{-- Dialog Modal Khusus Mentor melihat detail pembatalan --}}
        <dialog id="view-reason-{{ $booking->id }}" class="rounded-2xl p-0 w-full max-w-md backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
          <div class="p-6 text-left">
            <h4 class="text-base font-bold text-slate-900 mb-1">Detail Alasan Pembatalan</h4>
            <p class="text-xs text-slate-500 mb-4">Sesi ini dibatalkan dengan keterangan alasan sebagai berikut:</p>

            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 italic font-medium leading-relaxed">
              "{{ $booking->cancellation_reason ?? 'Tidak ada deskripsi alasan spesifik yang dicantumkan.' }}"
            </div>

            <div class="flex justify-end mt-5">
              <x-mentor.button type="button" variant="secondary" onclick="document.getElementById('view-reason-{{ $booking->id }}').close()">Tutup</x-mentor.button>
            </div>
          </div>
        </dialog>
      @endif
    @endif

  </div>
</x-mentor.card>
