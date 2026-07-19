<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Daftar member — halaman "Manajemen Member" (mengikuti desain admin-member.html).
     *
     * Catatan: berbeda dari mentor, member TIDAK punya status "pending" di
     * skema (tidak ada kolom is_verified) — member langsung aktif begitu
     * daftar. Jadi status di sini cuma 2: aktif / tidak aktif, diturunkan
     * dari users.is_active (sama seperti pola di DashboardController).
     */
    public function index(Request $request): View
    {
        $status       = $request->query('status', 'all');
        $subscription = $request->query('subscription', 'all');
        $sort         = $request->query('sort', 'latest');
        $q            = trim((string) $request->query('q', ''));

        $query = Member::with('user')->withCount('enrollments');

        match ($status) {
            'active'   => $query->whereHas('user', fn ($u) => $u->where('is_active', true)),
            'inactive' => $query->whereHas('user', fn ($u) => $u->where('is_active', false)),
            default    => null,
        };

        if (in_array($subscription, ['free', 'premium'], true)) {
            $query->where('subscription_type', $subscription);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('full_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$q}%"));
            });
        }

        match ($sort) {
            'name_asc'        => $query->orderBy('full_name'),
            'most_programs'   => $query->orderByDesc('enrollments_count'),
            default           => $query->latest(),
        };

        $members = $query->paginate(10)->withQueryString();

        $stats = [
            'total'    => Member::count(),
            'active'   => Member::whereHas('user', fn ($u) => $u->where('is_active', true))->count(),
            'inactive' => Member::whereHas('user', fn ($u) => $u->where('is_active', false))->count(),
            'premium'  => Member::where('subscription_type', 'premium')->count(),
        ];

        return view('admin.members.index', compact('members', 'stats', 'status', 'subscription', 'sort', 'q'));
    }

    /**
     * Detail satu member — profil, langganan, dan program yang diikuti.
     */
    public function show(Member $member): View
    {
        $member->load('user');

        $enrollments = $member->enrollments()
            ->with('workoutProgram.mentor')
            ->latest()
            ->get();

        $performance = [
            'total_programs' => $enrollments->count(),
            'completed'      => $enrollments->where('status', 'completed')->count(),
            'avg_progress'   => $enrollments->count() ? round($enrollments->avg('progress_pct'), 1) : null,
            'total_bookings' => $member->bookings()->count(),
        ];

        return view('admin.members.show', compact('member', 'enrollments', 'performance'));
    }

    /**
     * Nonaktifkan / aktifkan kembali akun member.
     */
    public function toggleActive(Member $member): RedirectResponse
    {
        abort_unless($member->user, 404);

        $nowActive = ! $member->user->is_active;
        $member->user->update(['is_active' => $nowActive]);

        AuditLog::record(
            action: $nowActive ? 'member_activate' : 'member_suspend',
            details: $nowActive
                ? "Akun member <strong>{$member->full_name}</strong> diaktifkan kembali."
                : "Akun member <strong>{$member->full_name}</strong> dinonaktifkan.",
            targetTable: 'members',
            targetId: $member->id,
        );

        $message = $nowActive
            ? "Member \"{$member->full_name}\" diaktifkan kembali."
            : "Member \"{$member->full_name}\" dinonaktifkan.";

        return redirect()->back()->with('success', $message);
    }
}
