<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    /**
     * READ-only overview: rata-rata penyelesaian member secara keseluruhan,
     * lintas seluruh program milik mentor, plus perbandingan performa antar program.
     *
     * Baris progres (WorkoutEnrollment) diisi & diperbarui oleh sisi Member saat
     * mereka menjalani programnya — halaman ini murni menampilkan agregatnya.
     */
    public function index(): View
    {
        $mentor = Auth::user()->mentor;

        $overall = [
            'unique_members'  => $mentor->totalUniqueMembers(),
            'avg_progress'    => $mentor->overallAverageProgress(),
            'completion_rate' => $mentor->overallCompletionRate(),
            'needs_attention' => $mentor->needsAttentionCount(),
        ];

        // Perbandingan per program, diurutkan dari rata-rata progres tertinggi.
        $programs = $mentor->workoutPrograms()
            ->withCount('enrollments')
            ->with('enrollments:id,workout_program_id,progress_pct,status')
            ->get()
            ->sortByDesc(fn ($p) => $p->averageProgress() ?? -1)
            ->values();

        // Member yang paling butuh perhatian, dirangkum lintas seluruh program.
        $attentionList = $mentor->enrollments()
            ->needsAttention()
            ->with(['member', 'workoutProgram'])
            ->orderBy('last_activity_at')
            ->take(6)
            ->get();

        return view('mentor.statistics.index', compact('overall', 'programs', 'attentionList'));
    }
}
