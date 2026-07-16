@extends('layouts.app')

@section('title', 'Program Latihan | Planet Fitness')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

@include('partials.navbar')

<div class="min-h-screen bg-slate-50 text-slate-900 antialiased font-sans">
    <div class="max-w-7xl mx-auto px-6 pt-10">
        <div class="text-xs font-bold text-emerald-800 tracking-widest uppercase mb-2">Manajemen Latihan</div>
        <h1 class="text-3xl font-extrabold tracking-tight">Program Latihan</h1>
        <p class="text-sm text-slate-500 mt-2 max-w-xl">
            Akses ratusan sesi latihan terstruktur dari mentor bersertifikat, lengkap dengan video panduan, set, dan repetisi.
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8 pb-20">
        <div class="flex flex-wrap gap-2.5 mb-7">
            @php
                // Menentukan rute aktif untuk tombol chip filter berdasarkan status login
                $routeAction = Auth::check() ? route('member.programs.index') : route('programs.preview');
            @endphp

            <a href="{{ $routeAction }}"
               class="text-xs font-semibold px-4 py-2 rounded-full border transition duration-200 no-underline {{ !request('category') ? 'bg-emerald-50 text-emerald-700 border-transparent' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
               Semua Program
            </a>
            @foreach(['Penurunan Berat Badan', 'Pembentukan Otot', 'Kardio', 'Fleksibilitas'] as $cat)
                <a href="{{ $routeAction . '?category=' . urlencode($cat) }}"
                   class="text-xs font-semibold px-4 py-2 rounded-full border transition duration-200 no-underline {{ request('category') == $cat ? 'bg-emerald-50 text-emerald-700 border-transparent' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                   {{ $cat }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($programs as $program)
                @php
                    // Video latihan tersimpan per-exercise (bukan per-program), jadi kita
                    // kirimkan seluruh daftar exercise sebagai JSON ke tiap kartu supaya
                    // JS bisa menampilkan & memutar video yang benar saat kartu diklik.
                    $exercisesPayload = $program->exercises->map(function ($exercise) {
                        return [
                            'id'    => $exercise->id,
                            'name'  => $exercise->name,
                            'video' => $exercise->video_url,
                            'sets'  => $exercise->sets,
                            'reps'  => $exercise->reps,
                            'summary' => $exercise->summaryLabel(),
                        ];
                    })->values();

                    $programPayload = [
                        'id'          => $program->id,
                        'title'       => $program->title,
                        'description' => $program->description,
                        'mentor'      => $program->mentor->full_name,
                        'exercises'   => $exercisesPayload,
                        'progressPct' => $program->my_progress_pct ?? null,
                    ];
                @endphp
                <div class="program-card bg-white border border-slate-200 rounded-2xl overflow-hidden flex flex-col transition duration-300 hover:-translate-y-1 hover:shadow-xl cursor-pointer"
                     data-program='@json($programPayload)'
                     onclick="loadProgram(this)">

                    <div class="h-36 flex items-center justify-center relative bg-gradient-to-br from-emerald-500 to-emerald-700">
                        <i class="ti ti-barbell text-4xl text-white"></i>
                        <span class="absolute top-3 right-3 bg-white/90 px-2.5 py-1 rounded-full text-[10px] font-bold text-slate-900 capitalize shadow-sm">
                            {{ $program->level }}
                        </span>
                    </div>

                    <div class="p-5 flex flex-col gap-2 flex-1">
                        <h3 class="text-base font-bold text-slate-900 leading-snug m-0">{{ $program->title }}</h3>
                        <div class="flex gap-3.5 text-xs text-slate-500">
                            <span class="flex items-center gap-1"><i class="ti ti-clock"></i> {{ $program->duration_weeks ?? 0 }} mgg</span>
                            <span class="flex items-center gap-1"><i class="ti ti-calendar-event"></i> {{ $program->sessions_per_week ?? 0 }} sesi/mgg</span>
                        </div>

                        <div class="flex items-center gap-2 text-xs text-slate-500 mt-auto pt-3 border-t border-slate-100">
                            <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-[10px] uppercase shrink-0">
                                {{ strtoupper(substr($program->mentor->full_name, 0, 2)) }}
                            </div>
                            <span class="truncate">{{ $program->mentor->full_name }} · Mentor</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-sm text-slate-500">
                    Belum ada program latihan yang tersedia untuk kategori ini.
                </div>
            @endforelse
        </div>

        <div class="mt-14 grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2">
                <div class="bg-slate-900 rounded-2xl aspect-video relative overflow-hidden shadow-lg">
                    <video id="active-video" class="w-full h-full object-contain bg-black hidden" controls playsinline></video>

                    <div id="video-placeholder" class="absolute inset-0 flex items-center justify-center before:absolute before:inset-0 before:bg-[radial-gradient(circle_at_30%_30%,rgba(16,185,129,0.2),transparent_60%)]">
                        <div class="w-16 h-16 rounded-full bg-white/95 flex items-center justify-center relative z-10 shadow-md">
                            <i class="ti ti-player-play-filled text-2xl text-emerald-700 ml-1"></i>
                        </div>
                    </div>

                    <div id="video-empty-state" class="absolute inset-0 hidden items-center justify-center text-center px-6">
                        <div>
                            <i class="ti ti-video-off text-3xl text-slate-500"></i>
                            <p class="text-sm text-slate-400 mt-2">Mentor belum mengunggah video untuk latihan ini.</p>
                        </div>
                    </div>
                </div>

                <div id="exercise-list" class="hidden flex-wrap gap-2 mt-4"></div>

                <div class="pt-5">
                    <h2 id="active-title" class="text-xl font-bold text-slate-900 mb-2">Pilih salah satu program</h2>
                    <p id="active-desc" class="text-sm text-slate-500 leading-relaxed">
                        Klik salah satu kartu program di atas untuk memuat detail target gerakan, set latihan, dan video panduan dari mentor secara live.
                    </p>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm relative overflow-hidden">
                <h4 class="text-sm font-bold text-slate-900 mb-4">Detail Target Gerakan</h4>

                <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-b-0 text-xs">
                    <div id="set-checkbox-btn" class="w-5 h-5 rounded-md border border-slate-300 flex items-center justify-center cursor-pointer transition duration-200">
                        </div>
                    <div class="flex-1 ml-3">
                        <div id="active-sets-target" class="font-semibold text-slate-900">0 Total Sets</div>
                        <div id="active-reps-target" class="text-slate-500 mt-0.5">0 Repetisi per Set</div>
                    </div>
                </div>

                {{-- HANYA MUNCUL JIKA USER SUDAH LOGIN --}}
                @auth
                    <div class="mt-5">
                        <div id="session-progress-label" class="text-[11px] font-semibold text-slate-400 mb-2 hidden"></div>
                        <button id="finish-session-btn" type="button" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-sm transition duration-200 border-none cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                            <i id="finish-session-icon" class="ti ti-circle-check"></i>
                            <span id="finish-session-label">Selesai Latihan Sesi Ini</span>
                        </button>
                    </div>
                @endauth

                {{-- HANYA MUNCUL JIKA USER ADALAH GUEST (BELUM LOGIN) --}}
                @guest
                    <button disabled class="w-full mt-5 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-300 text-slate-500 rounded-xl text-sm font-semibold cursor-not-allowed border-none">
                        <i class="ti ti-lock"></i> Kunci Fitur (Hanya Member)
                    </button>

                    <div class="mt-6 p-4 bg-gradient-to-br from-emerald-600 to-emerald-800 text-white rounded-xl text-center shadow-md">
                        <h5 class="font-bold text-sm mb-1 text-white m-0">Mau Akses Semua Program?</h5>
                        <p class="text-[11px] text-emerald-100 mt-1 mb-3 leading-relaxed">Daftar akun gratis sekarang untuk membuka ratusan video latihan dan melacak progress kebugaranmu.</p>
                        <a href="{{ route('register') }}" class="inline-block w-full py-2 bg-white text-emerald-700 font-bold text-xs rounded-lg no-underline hover:bg-emerald-50 transition">
                            Daftar Sekarang (Gratis)
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

<script>
    const videoEl        = document.getElementById('active-video');
    const placeholderEl  = document.getElementById('video-placeholder');
    const emptyStateEl   = document.getElementById('video-empty-state');
    const exerciseListEl = document.getElementById('exercise-list');
    const finishBtn      = document.getElementById('finish-session-btn');
    const finishIcon     = document.getElementById('finish-session-icon');
    const finishLabel    = document.getElementById('finish-session-label');
    const progressLabel  = document.getElementById('session-progress-label');

    // URL template (id program diganti saat dipakai) — hanya ada untuk user yang
    // sudah login, karena guest tidak boleh membuat baris progres di database.
    const enrollUrlTemplate   = @auth "{{ route('member.programs.enroll', ['program' => 'PROGRAM_ID']) }}" @else null @endauth;
    const progressUrlTemplate = @auth "{{ route('member.programs.progress', ['program' => 'PROGRAM_ID']) }}" @else null @endauth;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Daftar latihan (sesi) untuk program yang sedang dibuka + index sesi yang aktif.
    let currentExercises     = [];
    let currentExerciseIndex = -1;
    let currentProgramId     = null;

    // Catat bahwa member "mengambil" program ini (dipanggil sekali saat kartu
    // program diklik/dibuka) — inilah yang membuat program muncul di daftar
    // "Progres Member" pada halaman mentor.
    function enrollInProgram(programId) {
        if (!enrollUrlTemplate || !programId) return;
        fetch(enrollUrlTemplate.replace('PROGRAM_ID', programId), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        }).catch(() => {}); // panggilan latar belakang — kegagalan tidak boleh mengganggu UI
    }

    // Kirim persentase progres terbaru ke server (dipanggil setiap sesi ditandai selesai).
    function syncProgressToServer(programId, progressPct) {
        if (!progressUrlTemplate || !programId) return;
        fetch(progressUrlTemplate.replace('PROGRAM_ID', programId), {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ progress_pct: progressPct }),
        }).catch(() => {});
    }

    function showVideoState(state) {
        // state: 'placeholder' | 'empty' | 'playing'
        videoEl.classList.toggle('hidden', state !== 'playing');
        placeholderEl.classList.toggle('hidden', state !== 'placeholder');
        emptyStateEl.classList.toggle('hidden', state !== 'empty');
        emptyStateEl.classList.toggle('flex', state === 'empty');
    }

    // Render ulang tampilan seluruh chip sesi: sesi aktif disorot hijau solid,
    // sesi yang sudah ditandai selesai mendapat centang, sisanya tampilan default.
    function renderExerciseChips() {
        exerciseListEl.querySelectorAll('button').forEach((btn, index) => {
            const exercise = currentExercises[index];
            const isActive = index === currentExerciseIndex;

            btn.classList.remove(
                'bg-emerald-600', 'text-white', 'border-transparent',
                'bg-emerald-50', 'text-emerald-700', 'border-emerald-300',
                'bg-white', 'text-slate-600', 'border-slate-200'
            );

            if (isActive) {
                btn.classList.add('bg-emerald-600', 'text-white', 'border-transparent');
            } else if (exercise.completed) {
                btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
            } else {
                btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
            }

            const checkIcon = btn.querySelector('.chip-check-icon');
            if (checkIcon) {
                checkIcon.classList.toggle('hidden', !exercise.completed);
            }
        });
    }

    // Perbarui label progres ("Sesi 2 dari 4 · 1 selesai") dan status tombol.
    // progressLabel/finishBtn hanya ada di DOM untuk user yang sudah login,
    // jadi selalu dicek dulu supaya tidak error di halaman preview guest.
    function updateSessionProgressUI() {
        if (!progressLabel || !finishBtn) return;

        if (!currentExercises.length) {
            progressLabel.classList.add('hidden');
            return;
        }

        const total     = currentExercises.length;
        const doneCount = currentExercises.filter(ex => ex.completed).length;
        const allDone   = doneCount === total;

        progressLabel.classList.remove('hidden');
        progressLabel.innerText = allDone
            ? `Semua ${total} sesi latihan selesai`
            : `Sesi ${currentExerciseIndex + 1} dari ${total} · ${doneCount} selesai`;

        if (allDone) {
            finishBtn.disabled = true;
            finishIcon.className = 'ti ti-confetti';
            finishLabel.innerText = 'Program Latihan Selesai';
        } else {
            finishBtn.disabled = false;
            finishIcon.className = 'ti ti-circle-check';
            finishLabel.innerText = 'Selesai Latihan Sesi Ini';
        }
    }

    function playExercise(index) {
        const exercise = currentExercises[index];
        if (!exercise) return;

        currentExerciseIndex = index;

        if (exercise.video) {
            videoEl.src = exercise.video;
            videoEl.load();
            showVideoState('playing');
        } else {
            videoEl.pause();
            videoEl.removeAttribute('src');
            showVideoState('empty');
        }

        document.getElementById('active-sets-target').innerText = (exercise.sets ?? 0) + ' Total Sets';
        document.getElementById('active-reps-target').innerText = (exercise.reps ?? 0) + ' Repetisi Per Set';

        renderExerciseChips();
        updateSessionProgressUI();

        // Checkbox target sets mengikuti status selesai/belum dari sesi yang aktif.
        const chk = document.getElementById('set-checkbox-btn');
        if (chk) {
            chk.classList.toggle('bg-emerald-600', !!exercise.completed);
            chk.classList.toggle('border-transparent', !!exercise.completed);
            chk.classList.toggle('border-slate-300', !exercise.completed);
            chk.innerHTML = exercise.completed ? '<i class="ti ti-check text-white text-[10px]"></i>' : '';
        }
    }

    function loadProgram(cardEl) {
        const data = JSON.parse(cardEl.dataset.program);

        document.getElementById('active-title').innerText = data.title;
        document.getElementById('active-desc').innerHTML = (data.description ?? '') +
            `<br><br><small class="text-emerald-700 font-bold">Dipandu oleh: ${data.mentor}</small>`;

        currentProgramId = data.id ?? null;

        // Member membuka program ini → catat sebagai "mengambil program" di sisi
        // mentor (isi/perbarui baris workout_enrollments). Tidak berlaku untuk guest.
        enrollInProgram(currentProgramId);

        // Bangun daftar chip latihan (tiap latihan punya videonya sendiri).
        // Setiap latihan disimpan sebagai satu "sesi" dengan status selesai/belum sendiri.
        currentExercises = (data.exercises ?? []).map(exercise => ({ ...exercise, completed: false }));
        currentExerciseIndex = -1;
        exerciseListEl.innerHTML = '';

        // Pulihkan sesi yang sudah pernah ditandai selesai sebelumnya (tersimpan di
        // server sebagai progress_pct) supaya centangnya tidak hilang saat halaman
        // di-refresh. Progres disimpan sebagai persentase agregat, jadi kita tandai
        // N sesi pertama sebagai selesai — sesuai urutan sesi selalu diselesaikan
        // berurutan lewat tombol "Selesai Latihan Sesi Ini".
        const total = currentExercises.length;
        let resumeIndex = 0;
        if (total > 0 && typeof data.progressPct === 'number') {
            const doneCount = Math.min(total, Math.round((data.progressPct / 100) * total));
            for (let i = 0; i < doneCount; i++) {
                currentExercises[i].completed = true;
            }
            resumeIndex = doneCount < total ? doneCount : total - 1;
        }

        if (currentExercises.length > 0) {
            exerciseListEl.classList.remove('hidden');
            exerciseListEl.classList.add('flex');

            currentExercises.forEach((exercise, index) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'text-xs font-semibold px-3.5 py-1.5 rounded-full border transition duration-200 hover:bg-slate-100 inline-flex items-center gap-1.5';
                btn.innerHTML = `<i class="ti ti-circle-check-filled text-[13px] chip-check-icon hidden"></i>` +
                    `<span>${exercise.name}${exercise.summary ? ' · ' + exercise.summary : ''}</span>`;
                btn.addEventListener('click', () => playExercise(index));
                exerciseListEl.appendChild(btn);
            });

            playExercise(resumeIndex);
        } else {
            exerciseListEl.classList.add('hidden');
            exerciseListEl.classList.remove('flex');
            showVideoState('empty');
            document.getElementById('active-sets-target').innerText = '0 Total Sets';
            document.getElementById('active-reps-target').innerText = '0 Repetisi Per Set';
            if (progressLabel) progressLabel.classList.add('hidden');
        }
    }

    // Event interaktif klik centang target (hanya berfungsi jika elemen ada / tidak tertutup disabled)
    const checkboxBtn = document.getElementById('set-checkbox-btn');
    if (checkboxBtn) {
        checkboxBtn.addEventListener('click', function() {
            @auth
                this.classList.toggle('bg-emerald-600');
                this.classList.toggle('border-transparent');
                this.classList.toggle('border-slate-300');
                this.innerHTML = this.classList.contains('bg-emerald-600') ? '<i class="ti ti-check text-white text-[10px]"></i>' : '';
            @else
                alert('Silakan daftar atau login terlebih dahulu untuk menandai latihan!');
            @endauth
        });
    }

    // Tombol "Selesai Latihan Sesi Ini": menandai SESI yang sedang aktif sebagai
    // selesai (bukan seluruh hari latihan), lalu otomatis melanjutkan ke sesi
    // berikutnya. Jika sesi ini adalah yang terakhir, tampilkan status program selesai.
    if (finishBtn) {
        finishBtn.addEventListener('click', function() {
            if (currentExerciseIndex < 0 || !currentExercises.length) return;

            currentExercises[currentExerciseIndex].completed = true;

            const doneCount    = currentExercises.filter(ex => ex.completed).length;
            const progressPct  = Math.round((doneCount / currentExercises.length) * 100);
            syncProgressToServer(currentProgramId, progressPct);

            const nextIndex = currentExerciseIndex + 1;
            if (nextIndex < currentExercises.length) {
                playExercise(nextIndex);
            } else {
                renderExerciseChips();
                updateSessionProgressUI();
            }
        });
    }
</script>
@endsection
