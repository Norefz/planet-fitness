<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\WorkoutExercise;
use App\Models\WorkoutProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutExerciseController extends Controller
{
    /**
     * CREATE — tambahkan satu jenis latihan baru ke dalam program.
     */
    public function store(Request $request, WorkoutProgram $program): RedirectResponse
    {
        $this->authorizeOwner($program);

        $validated = $this->validateExercise($request);
        $validated['workout_program_id'] = $program->id;
        $validated['order_index'] = (int) $program->exercises()->max('order_index') + 1;

        WorkoutExercise::create($validated);

        return redirect()
            ->route('mentor.programs.edit', $program)
            ->with('success', 'Latihan "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    /**
     * UPDATE — perbarui detail satu jenis latihan.
     */
    public function update(Request $request, WorkoutProgram $program, WorkoutExercise $exercise): RedirectResponse
    {
        $this->authorizeExercise($program, $exercise);

        $exercise->update($this->validateExercise($request));

        return redirect()
            ->route('mentor.programs.edit', $program)
            ->with('success', 'Latihan "' . $exercise->name . '" berhasil diperbarui.');
    }

    /**
     * DELETE — hapus satu jenis latihan dari program.
     */
    public function destroy(WorkoutProgram $program, WorkoutExercise $exercise): RedirectResponse
    {
        $this->authorizeExercise($program, $exercise);

        $name = $exercise->name;
        $exercise->delete();

        return redirect()
            ->route('mentor.programs.edit', $program)
            ->with('success', 'Latihan "' . $name . '" berhasil dihapus.');
    }

    /**
     * Tukar urutan tampil dengan latihan tetangganya (naik/turun) — reordering
     * ringan tanpa perlu drag-and-drop/JS tambahan.
     */
    public function move(Request $request, WorkoutProgram $program, WorkoutExercise $exercise): RedirectResponse
    {
        $this->authorizeExercise($program, $exercise);

        $direction = $request->validate(['direction' => ['required', 'in:up,down']])['direction'];

        $neighbor = $direction === 'up'
            ? $program->exercises()->where('order_index', '<', $exercise->order_index)->orderByDesc('order_index')->first()
            : $program->exercises()->where('order_index', '>', $exercise->order_index)->orderBy('order_index')->first();

        if ($neighbor) {
            [$a, $b] = [$exercise->order_index, $neighbor->order_index];
            $exercise->update(['order_index' => $b]);
            $neighbor->update(['order_index' => $a]);
        }

        return redirect()->route('mentor.programs.edit', $program);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function validateExercise(Request $request): array
    {
        return $request->validate([
            'name'             => ['required', 'string', 'max:150'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'video_url'        => ['nullable', 'url', 'max:255'],
            'sets'             => ['nullable', 'integer', 'min:1', 'max:99'],
            'reps'             => ['nullable', 'integer', 'min:1', 'max:999'],
            'duration_seconds' => ['nullable', 'integer', 'min:1', 'max:3600'],
            'rest_seconds'     => ['nullable', 'integer', 'min:0', 'max:600'],
        ]);
    }

    private function authorizeOwner(WorkoutProgram $program): void
    {
        abort_unless($program->mentor_id === Auth::user()->mentor->id, 403, 'Kamu tidak memiliki akses ke program ini.');
    }

    private function authorizeExercise(WorkoutProgram $program, WorkoutExercise $exercise): void
    {
        $this->authorizeOwner($program);
        abort_unless($exercise->workout_program_id === $program->id, 404);
    }
}
