<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Mentor;
use App\Models\User;
use App\Models\WorkoutProgram;
use App\Models\Booking;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat card numbers ──────────────────────────────────────
        $totalMembers   = Member::count();
        $activeMembers  = Member::whereHas('user', fn($q) => $q->where('is_active', true))->count();
        $prevMembers    = Member::where('created_at', '<', now()->startOfMonth())->count();
        $memberGrowth   = $prevMembers > 0
            ? round((($totalMembers - $prevMembers) / $prevMembers) * 100)
            : 0;

        $totalMentors    = Mentor::where('is_verified', true)->count();
        $pendingMentorsCount  = Mentor::where('is_verified', false)
                                     ->whereHas('user', fn($q) => $q->where('is_active', true))
                                     ->count();
        $verifiedMentors = $totalMentors;
        $mentorGrowth    = 6; // placeholder — hitung sama seperti member jika perlu

        // Menghitung seluruh program latihan yang terdaftar (Opsi C)
        $totalPrograms   = WorkoutProgram::count();

        // Karena member otomatis memiliki akses ke semua program yang terbit ('published'),
        // Kita hitung estimasi total penayangan/akses (Total Member x Total Program Terbit)
        $programPublishedCount = WorkoutProgram::where('status', 'published')->count();
        $totalEnrollments      = $totalMembers * $programPublishedCount;
        $programGrowth         = 8; // placeholder

        $bookingsThisWeek    = Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $pendingBookings     = Booking::where('status', 'pending')->count();
        $bookingConfirmRate  = $bookingsThisWeek > 0
            ? round((Booking::where('status', 'confirmed')
                            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                            ->count() / $bookingsThisWeek) * 100)
            : 0;

        // Karena tidak menggunakan pelacakan progres per tabel pivot, default-kan ke 100%
        $avgCompletion = 100;

        // Mencari rata-rata rating dari tabel mentors (karena kolom rating ada di tabel mentors)
        $avgRating = Mentor::avg('rating') ?? 0;

        $stats = [
            'total_members'              => $totalMembers,
            'active_members'             => $activeMembers,
            'member_growth'              => $memberGrowth,
            'total_mentors'              => $totalMentors,
            'pending_mentors'            => $pendingMentorsCount,
            'verified_mentors'           => $verifiedMentors,
            'mentor_growth'              => $mentorGrowth,
            'total_programs'             => $totalPrograms,
            'total_enrollments'          => $totalEnrollments,
            'program_growth'             => $programGrowth,
            'bookings_this_week'         => $bookingsThisWeek,
            'pending_bookings'           => $pendingBookings,
            'booking_confirmation_rate'  => $bookingConfirmRate,
            'avg_completion'             => $avgCompletion,
            'avg_rating'                 => $avgRating,
        ];

        // ── Bar chart: pendaftaran member 7 bulan terakhir ─────────
        $monthlyData = collect(range(6, 0))->map(function ($i) {
            $month = now()->subMonths($i);
            return [
                'label' => $month->locale('id')->isoFormat('MMM'),
                'count' => Member::whereYear('created_at', $month->year)
                                 ->whereMonth('created_at', $month->month)
                                 ->count(),
            ];
        })->toArray();

        // ── Recent members (5 terbaru) ─────────────────────────────
        $recentMembers = Member::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // ── Pending mentor verifications (Variabel disinkronkan dengan Blade) ──
        $pendingMentors = Mentor::with('user')
            ->where('is_verified', false)
            ->whereHas('user', fn($q) => $q->where('is_active', true))
            ->latest()
            ->limit(4)
            ->get();

        // Audit logs are sensitive and only visible to the primary Super Admin.
        $canViewAdminLogs = (bool) (auth('admin')->user()?->superAdmin?->is_head);
        $recentLogs = $canViewAdminLogs
            ? AuditLog::latest('performed_at')->limit(5)->get()
            : collect();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyData',
            'recentMembers',
            'pendingMentors', // Nama variabel ini sekarang pas dengan @forelse di dashboard.blade.php
            'recentLogs',
            'canViewAdminLogs',
        ));
    }
}
