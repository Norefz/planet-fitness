<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\WorkoutEnrollment;
use App\Models\WorkoutProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    // 1. UNTUK GUEST (Belum Login) — Beri satu program contoh yang konsisten.
    public function guestIndex(Request $request)
    {
        $query = WorkoutProgram::where('status', 'published')->with(['mentor', 'exercises']);

        // Program gratis tidak berubah ketika guest mengganti filter, sehingga guest
        // tidak dapat membuka satu program berbeda pada setiap kategori.
        $freeProgram = (clone $query)->oldest('published_at')->first();

        // Tampilkan dua program lain sebagai teaser. Payload latihan/video untuk
        // program-program ini tidak pernah dikirim ke browser guest.
        $lockedPrograms = (clone $query)
            ->when($freeProgram, fn ($programs) => $programs->whereKeyNot($freeProgram->id))
            ->latest('published_at')
            ->take(2)
            ->get();

        $programs = collect([$freeProgram])->filter()->concat($lockedPrograms);
        $guestAccessibleProgramId = $freeProgram?->id;

        return view('member.program-latihan', compact('programs', 'guestAccessibleProgramId'));
    }


    // 2. UNTUK MEMBER (Sudah Login) — Tampilkan semua program tanpa batas
    public function index(Request $request)
    {
        // Eager-load 'exercises' juga — tanpa ini, video yang diunggah mentor
        // (disimpan per-exercise, bukan per-program) tidak pernah ikut terkirim
        // ke view, sehingga player di sisi member selalu kosong.
        $query = WorkoutProgram::where('status', 'published')->with(['mentor', 'exercises']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $programs = $query->latest()->get();

        // Tempelkan progres tersimpan milik member yang login ke tiap program,
        // supaya centang sesi yang sudah selesai tidak hilang saat halaman
        // di-refresh (sebelumnya progres hanya hidup di memori JS browser).
        $member = Auth::user()->member;
        if ($member) {
            $progressByProgram = WorkoutEnrollment::where('member_id', $member->id)
                ->whereIn('workout_program_id', $programs->pluck('id'))
                ->pluck('progress_pct', 'workout_program_id');

            $programs->each(function ($program) use ($progressByProgram) {
                $program->my_progress_pct = $progressByProgram->get($program->id);
            });
        }

        $guestAccessibleProgramId = null;

        return view('member.program-latihan', compact('programs', 'guestAccessibleProgramId'));
    }

    /**
     * Daftarkan member yang sedang login ke program ini (dipanggil via fetch()
     * saat member membuka/klik kartu programnya di halaman program-latihan).
     *
     * Sebelumnya tidak ada endpoint sama sekali untuk ini, sehingga tabel
     * workout_enrollments tidak pernah terisi dan mentor selalu melihat
     * "Belum ada member yang mengambil program ini" walau member sudah membuka
     * programnya.
     */
    public function enroll(WorkoutProgram $program): JsonResponse
    {
        $member = Auth::user()->member;
        abort_unless($member, 403);
        abort_unless($program->isPublished(), 404);

        $enrollment = WorkoutEnrollment::firstOrNew([
            'member_id'          => $member->id,
            'workout_program_id' => $program->id,
        ]);

        if (! $enrollment->exists) {
            $enrollment->status = 'active';
            $enrollment->progress_pct = 0;
            $enrollment->started_at = now();
        }

        $enrollment->last_activity_at = now();
        $enrollment->save();

        return response()->json([
            'status'       => $enrollment->status,
            'progress_pct' => $enrollment->progress_pct,
        ]);
    }

    /**
     * Perbarui persentase progres member pada program ini (dipanggil saat member
     * menandai sebuah sesi/latihan selesai di halaman program-latihan).
     */
    public function updateProgress(Request $request, WorkoutProgram $program): JsonResponse
    {
        $member = Auth::user()->member;
        abort_unless($member, 403);

        $validated = $request->validate([
            'progress_pct' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $enrollment = WorkoutEnrollment::firstOrNew([
            'member_id'          => $member->id,
            'workout_program_id' => $program->id,
        ]);

        if (! $enrollment->exists) {
            $enrollment->status = 'active';
            $enrollment->started_at = now();
        }

        $enrollment->progress_pct = $validated['progress_pct'];
        $enrollment->last_activity_at = now();

        if ($validated['progress_pct'] >= 100) {
            $enrollment->status = 'completed';
            $enrollment->completed_at = $enrollment->completed_at ?? now();
        } else {
            $enrollment->status = 'active';
            $enrollment->completed_at = null;
        }

        $enrollment->save();

        return response()->json([
            'status'       => $enrollment->status,
            'progress_pct' => $enrollment->progress_pct,
        ]);
    }
}
