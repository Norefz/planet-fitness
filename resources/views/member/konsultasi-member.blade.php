@extends('layouts.member')
@section('title', 'Konsultasi Mentor')

@section('content')

  {{-- ── Page header ── --}}
  <div class="mb-8">
    <div class="text-xs font-bold text-primary-600 tracking-widest uppercase mb-2">Penjadwalan & Interaksi</div>
    <h1 class="text-[28px] sm:text-3xl font-bold tracking-tight text-slate-900">Konsultasi Mentor</h1>
    <p class="text-sm text-slate-500 mt-1.5 max-w-lg">
      Booking sesi konsultasi langsung dengan mentor bersertifikat — dari pengajuan jadwal hingga sesi selesai.
    </p>
  </div>

  {{-- ── Flash messages ── --}}
  @if (session('success'))
    <div class="flex items-center gap-3 bg-primary-50 border border-primary-200 text-primary-800
                rounded-xl px-4 py-3 mb-6 text-sm font-medium">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700
                rounded-xl px-4 py-3 mb-6 text-sm font-medium">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      {{ session('error') }}
    </div>
  @endif

  {{-- ════════════════════════════════════════════════════
       SECTION 1: Pilih Mentor
  ════════════════════════════════════════════════════ --}}
  <div class="mb-10">
    <h2 class="text-base font-bold text-slate-900 mb-4">Cari Mentor</h2>

    @if ($mentors->isEmpty())
      <div class="text-sm text-slate-400 py-8 text-center">
        Belum ada mentor yang tersedia saat ini.
      </div>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="mentor-list">
        @foreach ($mentors as $mentor)
          <button
            type="button"
            onclick="selectMentor('{{ $mentor->id }}', '{{ $mentor->full_name }}')"
            data-mentor-id="{{ $mentor->id }}"
            class="mentor-card text-left p-4 rounded-2xl border-2 border-slate-200
                   hover:border-primary-400 hover:bg-primary-50/50
                   transition-all duration-200 cursor-pointer focus:outline-none
                   focus:border-primary-500">
            <div class="flex items-center gap-3">
              {{-- Avatar --}}
              <div class="w-11 h-11 rounded-full bg-gradient-to-br from-primary-400 to-primary-600
                          flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                {{ $mentor->initials() }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-bold text-slate-900">{{ $mentor->full_name }}</div>
                <div class="text-xs text-slate-500 truncate">
                  {{ $mentor->specialization ?? 'Fitness Trainer' }}
                </div>
              </div>
            </div>
            <div class="flex items-center gap-2 mt-3">
              @if ($mentor->certification)
                <span class="text-[11px] font-bold text-primary-700 bg-primary-100
                             px-2 py-0.5 rounded-full">Bersertifikat</span>
              @endif
              @if ($mentor->rating)
                <span class="text-[11px] text-slate-500">
                  ⭐ {{ number_format($mentor->rating, 1) }}
                </span>
              @endif
            </div>
          </button>
        @endforeach
      </div>
    @endif
  </div>

  {{-- ════════════════════════════════════════════════════
       SECTION 2: Form Booking (muncul setelah pilih mentor)
  ════════════════════════════════════════════════════ --}}
  <div id="booking-form-section" class="mb-10 hidden">
    <h2 class="text-base font-bold text-slate-900 mb-4">
      Jadwalkan Sesi —
      <span id="selected-mentor-name" class="text-primary-600"></span>
    </h2>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
      <form method="POST" action="{{ route('member.konsultasi.store') }}" id="booking-form">
        @csrf
        <input type="hidden" name="mentor_id" id="mentor_id_input" />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Tanggal & Waktu --}}
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">
              Tanggal & Waktu Sesi
            </label>
            <input
              type="datetime-local"
              name="scheduled_at"
              required
              min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
              class="px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm
                     text-slate-900 focus:outline-none focus:border-primary-400
                     focus:bg-white focus:ring-2 focus:ring-primary-100 transition-all" />
          </div>

          {{-- Durasi --}}
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">
              Durasi Sesi
            </label>
            <select
              name="duration_minutes"
              class="px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm
                     text-slate-900 focus:outline-none focus:border-primary-400
                     focus:bg-white focus:ring-2 focus:ring-primary-100 transition-all cursor-pointer">
              <option value="30">30 menit</option>
              <option value="60" selected>60 menit</option>
              <option value="90">90 menit</option>
            </select>
          </div>

          {{-- Topik --}}
          <div class="flex flex-col gap-1.5 sm:col-span-2">
            <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">
              Topik Konsultasi <span class="text-slate-400 font-normal">(opsional)</span>
            </label>
            <input
              type="text"
              name="topic"
              maxlength="255"
              placeholder="mis. Evaluasi program latihan, konsultasi nutrisi, penurunan berat badan..."
              class="px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm
                     text-slate-900 placeholder:text-slate-400
                     focus:outline-none focus:border-primary-400 focus:bg-white
                     focus:ring-2 focus:ring-primary-100 transition-all" />
          </div>

        </div>

        {{-- Info gratis --}}
        <div class="flex items-center gap-2 mt-5 text-xs text-slate-500 bg-slate-50
                    border border-slate-200 rounded-xl px-4 py-3">
          <svg class="w-4 h-4 text-primary-500 flex-shrink-0" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
          Konsultasi ini <strong class="text-primary-700">gratis</strong> — tidak ada biaya yang dikenakan.
          Setelah diajukan, mentor akan mengonfirmasi dan link Zoom akan tersedia secara otomatis.
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 mt-5">
          <button
            type="submit"
            class="flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700
                   text-white text-sm font-bold transition-all duration-200
                   hover:-translate-y-0.5 hover:shadow-md cursor-pointer border-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>
            </svg>
            Ajukan Permintaan Booking
          </button>
          <button
            type="button"
            onclick="cancelSelection()"
            class="px-4 py-3 rounded-xl text-slate-600 text-sm font-semibold
                   hover:bg-slate-100 transition-all duration-200 cursor-pointer border-none bg-transparent">
            Batal
          </button>
        </div>

      </form>
    </div>
  </div>

  {{-- ════════════════════════════════════════════════════
       SECTION 3: Booking aktif milik member
  ════════════════════════════════════════════════════ --}}
  <div class="mb-10">
    <h2 class="text-base font-bold text-slate-900 mb-4">Sesi Konsultasimu</h2>

    @if ($myBookings->isEmpty())
      <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-8 text-center">
        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor"
             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <div class="text-sm text-slate-500">Belum ada booking aktif. Pilih mentor di atas untuk mulai.</div>
      </div>
    @else
      <div class="flex flex-col gap-3">
        @foreach ($myBookings as $booking)
          <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 flex-wrap shadow-sm">

            {{-- Info booking --}}
            <div class="flex-1 min-w-[200px]">
              <div class="text-sm font-bold text-slate-900">
                {{ $booking->mentor->full_name ?? 'Mentor' }}
              </div>
              <div class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 flex-wrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <rect x="3" y="4" width="18" height="18" rx="2"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                {{ $booking->scheduled_at->translatedFormat('l, d M Y') }} ·
                {{ $booking->scheduled_at->format('H:i') }} WIB
                · {{ $booking->duration_minutes }} menit
                @if ($booking->topic)
                  <span class="text-slate-300">·</span> {{ $booking->topic }}
                @endif
              </div>

              {{-- Status badge --}}
              @php
                $badgeClass = match($booking->status) {
                  'pending'   => 'bg-amber-100 text-amber-800',
                  'confirmed' => 'bg-primary-100 text-primary-800',
                  default     => 'bg-slate-100 text-slate-600',
                };
              @endphp
              <span class="inline-block mt-2 text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $badgeClass }}">
                {{ $booking->statusLabel() }}
              </span>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-wrap">

              {{-- Link Zoom untuk member (join_url) --}}
              @if ($booking->status === 'confirmed' && $booking->meeting_url)
                <a
                  href="{{ $booking->meeting_url }}"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-600 hover:bg-primary-700
                         text-white text-xs font-bold transition-all duration-200 no-underline">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                       stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
                  </svg>
                  Gabung Zoom
                </a>
              @endif

              {{-- Batalkan (hanya jika pending) --}}
              @if ($booking->status === 'pending')
                <form method="POST"
                      action="{{ route('member.konsultasi.cancel', $booking) }}"
                      onsubmit="return confirm('Batalkan permintaan booking ini?');">
                  @csrf @method('PATCH')
                  <button
                    type="submit"
                    class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-600
                           border border-slate-200 hover:bg-red-50 hover:text-red-600
                           hover:border-red-200 transition-all duration-200 cursor-pointer bg-white">
                    Batalkan
                  </button>
                </form>
              @endif

            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- ════════════════════════════════════════════════════
       SECTION 4: Riwayat booking
  ════════════════════════════════════════════════════ --}}
  @if ($myHistory->isNotEmpty())
    <div>
      <h2 class="text-base font-bold text-slate-900 mb-4">Riwayat Sesi</h2>
      <div class="flex flex-col gap-3">
        @foreach ($myHistory as $booking)
          <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4
                      flex-wrap shadow-sm opacity-70">
            <div class="flex-1 min-w-[200px]">
              <div class="text-sm font-semibold text-slate-700">
                {{ $booking->mentor->full_name ?? 'Mentor' }}
              </div>
              <div class="text-xs text-slate-400 mt-1">
                {{ $booking->scheduled_at->translatedFormat('d M Y') }} ·
                {{ $booking->scheduled_at->format('H:i') }} WIB
                @if ($booking->topic) · {{ $booking->topic }} @endif
              </div>
              @php
                $histBadge = match($booking->status) {
                  'completed' => 'bg-blue-50 text-blue-700',
                  'cancelled' => 'bg-slate-100 text-slate-500',
                  default     => 'bg-slate-100 text-slate-500',
                };
              @endphp
              <span class="inline-block mt-2 text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $histBadge }}">
                {{ $booking->statusLabel() }}
              </span>
              @if ($booking->mentor_notes)
                <div class="mt-2 text-xs text-slate-500 italic bg-slate-50 rounded-lg px-3 py-2 border border-slate-100">
                  "{{ Str::limit($booking->mentor_notes, 100) }}"
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

@endsection

@push('scripts')
<script>
  // ── Pilih mentor → tampilkan form booking ──
  function selectMentor(mentorId, mentorName) {
    document.getElementById('mentor_id_input').value = mentorId;
    document.getElementById('selected-mentor-name').textContent = mentorName;

    // Highlight kartu yang dipilih
    document.querySelectorAll('.mentor-card').forEach(card => {
      const isSelected = card.dataset.mentorId === mentorId;
      card.classList.toggle('border-primary-500', isSelected);
      card.classList.toggle('bg-primary-50',       isSelected);
      card.classList.toggle('border-slate-200',   !isSelected);
    });

    // Tampilkan form & scroll ke sana
    const formSection = document.getElementById('booking-form-section');
    formSection.classList.remove('hidden');
    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // ── Batal pilih mentor ──
  function cancelSelection() {
    document.getElementById('mentor_id_input').value = '';
    document.getElementById('booking-form-section').classList.add('hidden');
    document.querySelectorAll('.mentor-card').forEach(card => {
      card.classList.remove('border-primary-500', 'bg-primary-50');
      card.classList.add('border-slate-200');
    });
  }
</script>
@endpush
