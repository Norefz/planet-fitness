<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi Mentor - Planet Fitness</title>

    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.design-tokens')
    @include('partials.global-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex flex-col justify-between">
    @include('partials.toast-container')

    <div>
        @include('partials.navbar')

        <main class="max-w-7xl mx-auto px-[5%] py-10 space-y-10">

            {{-- Alert Berhasil / Gagal --}}
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-bold shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error') || $errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-bold shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 p-8 rounded-2xl shadow-sm">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest block mb-1">Penjadwalan & Interaksi</span>
                <h2 class="text-3xl font-black text-slate-950">Konsultasi Video Live</h2>
                <p class="text-slate-500 text-sm mt-2 max-w-2xl leading-relaxed">
                    Booking sesi video konsultasi langsung dengan mentor bersertifikat kami — otomatis dibuatkan link Zoom setelah disetujui oleh mentor pilihan Anda.
                </p>
            </div>

            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 text-emerald-700 text-sm font-black">1</span>
                    Pilih Mentor
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(isset($mentors) && $mentors->count() > 0)
                        @foreach($mentors as $mentor)
                            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:border-emerald-500/50 hover:shadow-xl transition duration-300">
                                <div>
                                    <div class="flex items-center space-x-4 mb-4">
                                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-lg">
                                            {{ strtoupper(substr($mentor->user->name ?? 'M', 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-900">{{ $mentor->user->name ?? 'Mentor' }}</h4>
                                            <p class="text-xs text-slate-500">{{ $mentor->specialization ?? 'Pelatih Kebugaran' }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 mb-3">
                                        <i class="fa-solid fa-certificate mr-1"></i> Bersertifikat
                                    </span>
                                </div>

                                @guest
                                    <a href="{{ route('login') }}" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider text-center transition block no-underline">
                                        Login untuk Booking
                                    </a>
                                @else
                                    <button type="button" onclick="selectMentor('{{ $mentor->id }}', '{{ $mentor->user->name }}')" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition">
                                        Booking {{ explode(' ', $mentor->user->name)[0] }}
                                    </button>
                                @endguest
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-lg">RA</div>
                                <div><h4 class="font-bold text-slate-900">Rini Andini</h4><p class="text-xs text-slate-500">Pelatih Kebugaran</p></div>
                            </div>
                            <a href="{{ route('login') }}" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-xs text-center block no-underline">Login untuk Booking</a>
                        </div>
                    @endif
                </div>
            </div>

            <div id="booking-section" class="bg-white rounded-2xl border border-slate-200 p-8 space-y-6 shadow-sm relative overflow-hidden">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 text-emerald-700 text-sm font-black">2</span>
                    Atur Jadwal Konsultasi — <span id="selectedMentorName" class="text-emerald-600 font-black">Pilih mentor di atas</span>
                </h3>

                <form action="{{ Auth::check() ? route('member.konsultasi.store') : '#' }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="mentor_id" id="mentorIdInput" value="">

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Topik Konsultasi</label>
                        <input type="text" name="topic" placeholder="Misal: Konsultasi Penurunan Berat Badan" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm focus:outline-emerald-500" {{ Auth::guest() ? 'disabled' : '' }}>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pilih Waktu & Tanggal Sesi</label>
                        <input type="datetime-local" name="scheduled_at" required class="px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-emerald-500" {{ Auth::guest() ? 'disabled' : '' }}>
                        <p class="text-[11px] text-slate-400 mt-1">* Waktu konsultasi menggunakan zona waktu WIB.</p>
                    </div>

                    @guest
                        <a href="{{ route('login') }}" class="w-full py-4 bg-slate-800 hover:bg-slate-950 text-white font-black rounded-xl text-sm transition tracking-wider uppercase flex items-center justify-center gap-2 no-underline">
                            <i class="fa-solid fa-lock"></i> Login untuk Membuat Jadwal
                        </a>
                    @else
                        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-xl text-sm transition tracking-wider uppercase flex items-center justify-center gap-2 shadow-md shadow-emerald-700/10">
                            <i class="fa-solid fa-paper-plane"></i> Ajukan Permintaan Booking
                        </button>
                    @endguest
                </form>

                @guest
                    <div class="absolute inset-0 bg-slate-50/10 backdrop-blur-[1px] pointer-events-none select-none"></div>
                @endguest
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 space-y-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-emerald-600"></i>
                    Sesi Konsultasimu
                </h3>

                <div class="space-y-4">
                    @guest
                        @else
                        @if((isset($myBookings) && $myBookings->isEmpty()) && (isset($myHistory) && $myHistory->isEmpty()))
                            <div class="text-center py-8 text-slate-400 text-sm">
                                <i class="fa-regular fa-folder-open text-2xl block mb-2"></i> Belum ada riwayat atau pengajuan jadwal konsultasi.
                            </div>
                        @else
                            @php
                                $latestBooking = $myBookings->first() ?? $myHistory->first();
                            @endphp

                            @if(isset($myBookings))
                                @foreach($myBookings as $booking)
                                    <div class="border border-slate-200 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:shadow-md transition duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($booking->mentor->user->name ?? 'M', 0, 2)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm">
                                                    {{ $booking->mentor->user->name ?? 'Mentor' }} · {{ $booking->topic ?? 'Konsultasi Kebugaran' }}
                                                </h4>
                                                <p class="text-xs text-slate-500 mt-1">
                                                    <i class="fa-regular fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($booking->scheduled_at)->isoFormat('D MMMM YYYY · HH:mm') }} WIB
                                                </p>

                                                @if($booking->status === 'confirmed')
                                                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <i class="fa-solid fa-circle-check mr-1"></i> Dikonfirmasi
                                                    </span>
                                                @else
                                                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        <i class="fa-solid fa-clock mr-1"></i> Menunggu Konfirmasi
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                                            @if($booking->status === 'confirmed' && $booking->meeting_url)
                                                <a href="{{ $booking->meeting_url }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl flex items-center gap-1.5 transition no-underline">
                                                    <i class="fa-solid fa-video"></i> Gabung Zoom
                                                </a>
                                            @endif

                                            @if($booking->status === 'pending')
                                                <form action="{{ route('member.konsultasi.cancel', $booking->id) }}" method="POST" id="cancel-form-{{ $booking->id }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="cancellation_reason" id="reason-{{ $booking->id }}">
                                                    <button type="button" onclick="openCancelModal('{{ $booking->id }}')" class="px-5 py-2.5 border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-700 text-xs font-bold rounded-xl transition">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if(isset($myHistory))
                                @foreach($myHistory as $historySession)
                                    <div class="border border-slate-200 bg-slate-50/40 rounded-2xl p-6 opacity-75" x-data="{ noteOpen: false }">
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold">
                                                    {{ strtoupper(substr($historySession->mentor->user->name ?? 'M', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-700 text-sm">
                                                        {{ $historySession->mentor->user->name ?? 'Mentor' }} · {{ $historySession->topic ?? 'Konsultasi Selesai' }}
                                                    </h4>
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        <i class="fa-regular fa-calendar mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($historySession->scheduled_at)->isoFormat('D MMMM YYYY · HH:mm') }} WIB
                                                    </p>

                                                    @if($historySession->status === 'completed')
                                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                            <i class="fa-solid fa-check mr-1"></i> Selesai
                                                        </span>
                                                    @else
                                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                            <i class="fa-solid fa-xmark mr-1"></i> Dibatalkan
                                                        </span>
                                                        {{-- PERBAIKAN: Menampilkan alasan pembatalan agar member bisa melihatnya langsung --}}
                                                        @if($historySession->cancellation_reason)
                                                            <p class="text-xs text-rose-600 font-medium mt-1.5 bg-rose-50 border border-rose-100 rounded-lg px-2.5 py-1 block">
                                                                <strong>Alasan Batal:</strong> "{{ $historySession->cancellation_reason }}"
                                                            </p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            @if($historySession->status === 'completed' && $historySession->mentor_notes)
                                                <div class="w-full sm:w-auto text-right">
                                                    <button type="button" @click="noteOpen = !noteOpen" class="px-5 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl transition shadow-sm flex items-center gap-1.5 ml-auto">
                                                        <i class="fa-regular fa-comment-dots"></i>
                                                        <span x-text="noteOpen ? 'Tutup Catatan' : 'Lihat Catatan'"></span>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>

                                        @if($historySession->status === 'completed' && $historySession->mentor_notes)
                                            <div x-show="noteOpen" x-cloak
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                                 x-transition:enter-end="opacity-100 translate-y-0"
                                                 class="mt-4 pt-4 border-t border-slate-200 text-xs text-slate-600 leading-6 whitespace-pre-line">
                                                <span class="font-bold text-slate-700">Catatan Mentor:</span><br>
                                                {{ $historySession->mentor_notes }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    @endguest
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center gap-6 text-xs font-bold text-slate-400">
                    <div class="flex items-center gap-1.5 text-emerald-600">
                        <i class="fa-solid fa-circle-check"></i> Pending
                    </div>
                    <div class="h-px bg-slate-200 w-8"></div>
                    @if(isset($latestBooking) && in_array($latestBooking->status, ['confirmed', 'completed']))
                        <div class="flex items-center gap-1.5 text-emerald-600">
                            <i class="fa-solid fa-circle-check"></i> Dikonfirmasi
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <span class="w-4 h-4 rounded-full border border-slate-300 flex items-center justify-center text-[9px]">2</span> Dikonfirmasi
                        </div>
                    @endif
                    <div class="h-px bg-slate-200 w-8"></div>
                    @if(isset($latestBooking) && $latestBooking->status === 'completed')
                        <div class="flex items-center gap-1.5 text-emerald-600">
                            <i class="fa-solid fa-circle-check"></i> Selesai
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 text-slate-400">
                            <span class="w-4 h-4 rounded-full border border-slate-300 flex items-center justify-center text-[9px]">3</span> Selesai
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <div id="custom-cancel-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeCancelModal()"></div>
        <div class="bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 relative z-10 shadow-xl animate-[fadeIn_0.2s_ease-out]">
            <h4 class="text-base font-black text-slate-950 mb-1">Alasan Pembatalan Sesi</h4>
            <p class="text-xs text-slate-500 mb-4">Berikan alasan mengapa Anda membatalkan permintaan konsultasi ini agar mentor mengetahui kendala Anda.</p>

            <textarea
                id="modal-textarea-reason"
                rows="3"
                placeholder="mis. Maaf saya ada urusan darurat atau jadwal kerja bentrok di jam tersebut..."
                class="w-full px-4 py-3 text-sm text-slate-800 placeholder-slate-400 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition resize-none"
            ></textarea>
            <p id="modal-textarea-error" class="hidden mt-1.5 text-xs font-semibold text-red-600">
                Alasan pembatalan wajib diisi.
            </p>

            <div class="flex justify-end gap-2.5 mt-5 text-xs font-bold">
                <button type="button" onclick="closeCancelModal()" class="px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition">
                    Kembali
                </button>
                <button type="button" onclick="submitCancelModal()" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition shadow-md shadow-rose-700/10">
                    Konfirmasi Batalkan
                </button>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <script>
        function selectMentor(id, name) {
            document.getElementById('selectedMentorName').textContent = name;
            document.getElementById('mentorIdInput').value = id;
            document.getElementById('booking-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        let currentActiveBookingId = null;

        function openCancelModal(bookingId) {
            currentActiveBookingId = bookingId;
            const textarea = document.getElementById('modal-textarea-reason');
            textarea.value = '';
            textarea.classList.remove('field-invalid', 'field-shake');
            document.getElementById('modal-textarea-error').classList.add('hidden');
            document.getElementById('custom-cancel-modal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('custom-cancel-modal').classList.add('hidden');
            currentActiveBookingId = null;
        }

        function submitCancelModal() {
            let textarea = document.getElementById('modal-textarea-reason');
            let errorMsg = document.getElementById('modal-textarea-error');
            let reasonText = textarea.value;
            if (!reasonText || reasonText.trim() === "") {
                textarea.classList.add('field-invalid', 'field-shake');
                errorMsg.classList.remove('hidden');
                textarea.focus();
                setTimeout(() => textarea.classList.remove('field-shake'), 500);
                if (typeof pfToast === 'function') pfToast('error', 'Alasan pembatalan wajib diisi.');
                return;
            }
            document.getElementById('reason-' + currentActiveBookingId).value = reasonText;
            document.getElementById('cancel-form-' + currentActiveBookingId).submit();
        }

        document.getElementById('modal-textarea-reason')?.addEventListener('input', function() {
            this.classList.remove('field-invalid');
            document.getElementById('modal-textarea-error').classList.add('hidden');
        });
    </script>
</body>
</html>
