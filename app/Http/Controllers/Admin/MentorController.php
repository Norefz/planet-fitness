<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Mentor;
use App\Models\WorkoutProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorController extends Controller
{
    /**
     * Daftar mentor — dipakai untuk halaman "Manajemen Mentor" (sesuai desain admin-mentor.html).
     *
     * Status mentor diturunkan dari 2 kolom yang sudah ada (tidak perlu migrasi baru):
     *   - pending  : is_verified = false, user aktif   (belum ditinjau admin)
     *   - verified : is_verified = true,  user aktif   (mentor aktif)
     *   - rejected : is_verified = false, user nonaktif (sudah ditinjau & ditolak/disuspend)
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $q      = trim((string) $request->query('q', ''));

        $query = Mentor::with('user')->withCount('workoutPrograms');

        match ($status) {
            'pending'  => $query->where('is_verified', false)->whereHas('user', fn ($u) => $u->where('is_active', true)),
            'verified' => $query->where('is_verified', true)->whereHas('user', fn ($u) => $u->where('is_active', true)),
            'rejected' => $query->where('is_verified', false)->whereHas('user', fn ($u) => $u->where('is_active', false)),
            default    => null,
        };

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('full_name', 'like', "%{$q}%")
                    ->orWhere('specialization', 'like', "%{$q}%")
                    ->orWhere('certification', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$q}%"));
            });
        }

        $mentors = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'verified' => Mentor::where('is_verified', true)->whereHas('user', fn ($u) => $u->where('is_active', true))->count(),
            'pending'  => Mentor::where('is_verified', false)->whereHas('user', fn ($u) => $u->where('is_active', true))->count(),
            'rejected' => Mentor::where('is_verified', false)->whereHas('user', fn ($u) => $u->where('is_active', false))->count(),
            'programs' => WorkoutProgram::count(),
            'avg_rating' => round((float) (Mentor::where('is_verified', true)->avg('rating') ?? 0), 1),
        ];

        $pendingMentors = Mentor::with('user')
            ->where('is_verified', false)
            ->whereHas('user', fn ($u) => $u->where('is_active', true))
            ->latest()
            ->take(6)
            ->get();

        return view('admin.mentors.index', compact('mentors', 'stats', 'pendingMentors', 'status', 'q'));
    }

    /**
     * Detail satu mentor — profil, program latihan, dan ringkasan performa.
     */
    public function show(Mentor $mentor): View
    {
        $mentor->load('user');

        $programs = $mentor->workoutPrograms()->withCount('enrollments')->latest()->get();

        $performance = [
            'total_programs'   => $programs->count(),
            'published'        => $programs->where('status', 'published')->count(),
            'unique_members'   => $mentor->totalUniqueMembers(),
            'avg_progress'     => $mentor->overallAverageProgress(),
            'completion_rate'  => $mentor->overallCompletionRate(),
            'needs_attention'  => $mentor->needsAttentionCount(),
            'total_bookings'   => $mentor->bookings()->count(),
        ];

        return view('admin.mentors.show', compact('mentor', 'programs', 'performance'));
    }

    /**
     * Setujui atau tolak pendaftaran mentor (tombol "Setuju" / "Tolak").
     */
    public function verify(Request $request, Mentor $mentor): RedirectResponse
    {
        $action = $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ])['action'];

        if ($action === 'approve') {
            $mentor->update(['is_verified' => true]);

            if ($mentor->user && ! $mentor->user->is_active) {
                $mentor->user->update(['is_active' => true]);
            }

            AuditLog::record(
                action: 'mentor_verify',
                details: "Mentor <strong>{$mentor->full_name}</strong> disetujui dan diverifikasi.",
                targetTable: 'mentors',
                targetId: $mentor->id,
            );

            $message = "Mentor \"{$mentor->full_name}\" berhasil diverifikasi.";
        } else {
            $mentor->update(['is_verified' => false]);

            if ($mentor->user) {
                $mentor->user->update(['is_active' => false]);
            }

            AuditLog::record(
                action: 'mentor_reject',
                details: "Pendaftaran mentor <strong>{$mentor->full_name}</strong> ditolak.",
                targetTable: 'mentors',
                targetId: $mentor->id,
            );

            $message = "Pendaftaran mentor \"{$mentor->full_name}\" ditolak.";
        }

        return redirect()->route('admin.mentors.index')->with('success', $message);
    }

    /**
     * Suspend / aktifkan kembali akun mentor yang sudah terverifikasi.
     */
    public function toggleActive(Mentor $mentor): RedirectResponse
    {
        abort_unless($mentor->user, 404);

        $nowActive = ! $mentor->user->is_active;
        $mentor->user->update(['is_active' => $nowActive]);

        AuditLog::record(
            action: $nowActive ? 'mentor_activate' : 'mentor_suspend',
            details: $nowActive
                ? "Akun mentor <strong>{$mentor->full_name}</strong> diaktifkan kembali."
                : "Akun mentor <strong>{$mentor->full_name}</strong> disuspend.",
            targetTable: 'mentors',
            targetId: $mentor->id,
        );

        $message = $nowActive
            ? "Mentor \"{$mentor->full_name}\" diaktifkan kembali."
            : "Mentor \"{$mentor->full_name}\" disuspend.";

        return redirect()->back()->with('success', $message);
    }
}
