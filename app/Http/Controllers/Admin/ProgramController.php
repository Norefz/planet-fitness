<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\WorkoutEnrollment;
use App\Models\WorkoutProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    /**
     * Daftar program latihan — grid kartu (mengikuti desain admin-program.html).
     */
    public function index(Request $request): View
    {
        $status   = $request->query('status', 'all');
        $category = $request->query('category', 'all');
        $level    = $request->query('level', 'all');
        $sort     = $request->query('sort', 'popular');
        $q        = trim((string) $request->query('q', ''));

        $query = WorkoutProgram::with('mentor')
            ->withCount([
                'enrollments',
                'enrollments as completed_enrollments_count' => fn ($q) => $q->where('workout_enrollments.status', 'completed'),
            ]);

        match ($status) {
            'published' => $query->where('status', 'published'),
            'draft'     => $query->where('status', 'draft'),
            default     => null,
        };

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        if (in_array($level, ['pemula', 'menengah', 'lanjutan'], true)) {
            $query->where('level', $level);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhereHas('mentor', fn ($m) => $m->where('full_name', 'like', "%{$q}%"));
            });
        }

        match ($sort) {
            'latest'     => $query->latest(),
            'completion' => $query->orderByDesc('completed_enrollments_count'),
            default      => $query->orderByDesc('enrollments_count'), // 'popular' — jumlah member terbanyak
        };

        $programs = $query->paginate(9)->withQueryString();

        // Kategori yang benar-benar ada di data (bukan daftar statis), supaya filter selalu akurat.
        $categories = WorkoutProgram::query()->distinct()->orderBy('category')->pluck('category');

        $totalEnrollments = WorkoutEnrollment::count();
        $completedEnrollments = WorkoutEnrollment::where('status', 'completed')->count();

        $stats = [
            'published'        => WorkoutProgram::where('status', 'published')->count(),
            'draft'            => WorkoutProgram::where('status', 'draft')->count(),
            'total_enrollments'=> $totalEnrollments,
            'completion_rate'  => $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : null,
            'contributing_mentors' => WorkoutProgram::where('status', 'published')->distinct('mentor_id')->count('mentor_id'),
        ];

        return view('admin.programs.index', compact('programs', 'stats', 'categories', 'status', 'category', 'level', 'sort', 'q'));
    }

    /**
     * Detail satu program — deskripsi, latihan, dan performa member.
     */
    public function show(WorkoutProgram $program): View
    {
        $program->load('mentor', 'exercises');

        $performance = [
            'enrolled_count'   => $program->enrolledCount(),
            'average_progress' => $program->averageProgress(),
            'completion_rate'  => $program->completionRate(),
        ];

        return view('admin.programs.show', compact('program', 'performance'));
    }

    /**
     * Publikasikan program draf, atau kembalikan program terpublikasi ke draf ("arsipkan").
     */
    public function togglePublish(WorkoutProgram $program): RedirectResponse
    {
        $nowPublished = ! $program->isPublished();

        $program->update([
            'status'       => $nowPublished ? 'published' : 'draft',
            'published_at' => $nowPublished ? now() : $program->published_at,
        ]);

        AuditLog::record(
            action: 'program_publish',
            details: $nowPublished
                ? "Program <strong>{$program->title}</strong> dipublikasikan oleh admin."
                : "Program <strong>{$program->title}</strong> dikembalikan ke draf (diarsipkan) oleh admin.",
            targetTable: 'workout_programs',
            targetId: $program->id,
        );

        $message = $nowPublished
            ? "Program \"{$program->title}\" berhasil dipublikasikan."
            : "Program \"{$program->title}\" diarsipkan (dikembalikan ke draf).";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Hapus program secara permanen.
     */
    public function destroy(WorkoutProgram $program): RedirectResponse
    {
        $title = $program->title;
        $id = $program->id;
        $program->delete();

        AuditLog::record(
            action: 'program_delete',
            details: "Program <strong>{$title}</strong> dihapus oleh admin.",
            targetTable: 'workout_programs',
            targetId: $id,
        );

        return redirect()->route('admin.programs')->with('success', "Program \"{$title}\" berhasil dihapus.");
    }
}
