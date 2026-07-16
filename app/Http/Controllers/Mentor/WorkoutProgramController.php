<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkoutProgramController extends Controller
{
    /**
     * READ — daftar seluruh program milik mentor yang sedang login.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();
        $mentor = $user->mentorProfile();

        $query = $mentor->workoutPrograms()->withCount(['enrollments', 'exercises'])->with('enrollments:id,workout_program_id,progress_pct,status')->latest();

        if ($request->filled('status') && in_array($request->status, ['draft', 'published'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $programs = $query->paginate(8)->withQueryString();

        $stats = [
            'total'     => $mentor->workoutPrograms()->count(),
            'published' => $mentor->workoutPrograms()->published()->count(),
            'draft'     => $mentor->workoutPrograms()->draft()->count(),
        ];

        return view('mentor.programs.index', compact('programs', 'stats'));
    }

    /**
     * READ — statistik progres member untuk satu program spesifik ("per workoutplan").
     */
    public function show(WorkoutProgram $program): View
    {
        $this->authorizeOwner($program);

        $program->load('exercises');

        $enrollmentsQuery = $program->enrollments()->with('member');

        if (request('filter') === 'attention') {
            $enrollmentsQuery->needsAttention();
        } elseif (request('filter') === 'completed') {
            $enrollmentsQuery->completed();
        } elseif (request('filter') === 'active') {
            $enrollmentsQuery->where('status', 'active');
        }

        $sort = request('sort', 'progress_desc');
        $enrollmentsQuery->orderBy(
            $sort === 'oldest' ? 'started_at' : 'progress_pct',
            $sort === 'progress_asc' ? 'asc' : ($sort === 'oldest' ? 'asc' : 'desc')
        );

        $enrollments = $enrollmentsQuery->paginate(10)->withQueryString();

        $stats = [
            'enrolled'        => $program->enrolledCount(),
            'avg_progress'    => $program->averageProgress(),
            'completion_rate' => $program->completionRate(),
            'needs_attention' => $program->enrollments()->needsAttention()->count(),
        ];

        return view('mentor.programs.show', compact('program', 'enrollments', 'stats'));
    }

    /**
     * CREATE (form) — tampilkan form pembuatan program baru.
     */
    public function create(): View
    {
        $program = new WorkoutProgram();

        return view('mentor.programs.create', compact('program'));
    }

    /**
     * CREATE (simpan) — simpan program baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProgram($request);

        $isPublish = $request->input('action') === 'publish';
        $validated['status'] = $isPublish ? 'published' : 'draft';
        $validated['published_at'] = $isPublish ? now() : null;
        /** @var User $user */
        $user = Auth::user();
        $validated['mentor_id'] = $user->mentorProfile()->id;

        $program = WorkoutProgram::create($validated);

        return redirect()
            ->route('mentor.programs.edit', $program)
            ->with('success', ($isPublish
                ? 'Program latihan berhasil dipublikasikan.'
                : 'Program latihan berhasil disimpan sebagai draf.') . ' Sekarang tambahkan latihannya di bawah.');
    }

    /**
     * UPDATE (form) — tampilkan form edit, hanya untuk pemilik program.
     */
    public function edit(WorkoutProgram $program): View
    {
        $this->authorizeOwner($program);

        $program->load('exercises');

        return view('mentor.programs.edit', compact('program'));
    }

    /**
     * UPDATE (simpan) — perbarui data program.
     */
    public function update(Request $request, WorkoutProgram $program): RedirectResponse
    {
        $this->authorizeOwner($program);

        $validated = $this->validateProgram($request);

        $isPublish = $request->input('action') === 'publish';
        $validated['status'] = $isPublish ? 'published' : 'draft';
        if ($isPublish && ! $program->isPublished()) {
            $validated['published_at'] = now();
        }

        $program->update($validated);

        return redirect()
            ->route('mentor.programs.index')
            ->with('success', 'Program "' . $program->title . '" berhasil diperbarui.');
    }

    /**
     * DELETE — hapus program milik mentor.
     */
    public function destroy(WorkoutProgram $program): RedirectResponse
    {
        $this->authorizeOwner($program);

        $title = $program->title;
        $program->delete();

        return redirect()
            ->route('mentor.programs.index')
            ->with('success', 'Program "' . $title . '" berhasil dihapus.');
    }

    /**
     * Aksi cepat: pindah status Draf <-> Dipublikasikan langsung dari daftar.
     */
    public function toggleStatus(WorkoutProgram $program): RedirectResponse
    {
        $this->authorizeOwner($program);

        if ($program->isPublished()) {
            $program->update(['status' => 'draft']);
            $message = 'Program dipindahkan ke draf.';
        } else {
            $program->update(['status' => 'published', 'published_at' => now()]);
            $message = 'Program berhasil dipublikasikan.';
        }

        return redirect()->route('mentor.programs.index')->with('success', $message);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function validateProgram(Request $request): array
    {
        return $request->validate([
            'title'              => ['required', 'string', 'max:150'],
            'category'           => ['required', 'string', 'max:50'],
            'level'              => ['required', 'in:pemula,menengah,lanjutan'],
            'description'        => ['required', 'string', 'max:2000'],
            'duration_weeks'     => ['nullable', 'integer', 'min:1', 'max:52'],
            'sessions_per_week'  => ['nullable', 'integer', 'min:1', 'max:14'],
        ]);
    }

    private function authorizeOwner(WorkoutProgram $program): void
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($program->mentor_id === $user->mentorProfile()->id, 403, 'Kamu tidak memiliki akses ke program ini.');
    }
}
