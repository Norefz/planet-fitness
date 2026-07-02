<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
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
        $mentor = Auth::user()->mentor;

        $query = $mentor->workoutPrograms()->latest();

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
        $validated['mentor_id'] = Auth::user()->mentor->id;

        WorkoutProgram::create($validated);

        return redirect()
            ->route('mentor.programs.index')
            ->with('success', $isPublish
                ? 'Program latihan berhasil dipublikasikan.'
                : 'Program latihan berhasil disimpan sebagai draf.');
    }

    /**
     * UPDATE (form) — tampilkan form edit, hanya untuk pemilik program.
     */
    public function edit(WorkoutProgram $program): View
    {
        $this->authorizeOwner($program);

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
            'sets'               => ['nullable', 'integer', 'min:1', 'max:99'],
            'reps'               => ['nullable', 'integer', 'min:1', 'max:999'],
            'video_url'          => ['nullable', 'url', 'max:255'],
        ]);
    }

    private function authorizeOwner(WorkoutProgram $program): void
    {
        abort_unless($program->mentor_id === Auth::user()->mentor->id, 403, 'Kamu tidak memiliki akses ke program ini.');
    }
}
