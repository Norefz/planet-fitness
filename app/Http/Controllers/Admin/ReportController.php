<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Member;
use App\Models\Mentor;
use App\Models\WorkoutEnrollment;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Laporan & Analitik — ringkasan performa platform.
     * Semua angka dihitung langsung dari data asli (bukan dummy),
     * mengikuti pola perhitungan yang sama dengan DashboardController.
     */
    public function index(Request $request): View
    {
        // ── Rentang periode (tab 7 / 30 / 90 hari) ─────────────────────
        $period = (int) $request->query('period', 30);
        if (! in_array($period, [7, 30, 90], true)) {
            $period = 30;
        }
        $periodLabel = "{$period} hari";
        $cutoff = now()->subDays($period);

        // ── KPI: Total Member ───────────────────────────────────────────
        $totalMembers = Member::count();
        $prevMembers  = Member::where('created_at', '<', $cutoff)->count();
        $memberGrowth = $this->growthPct($totalMembers, $prevMembers);

        // ── KPI: Total Enrollment ────────────────────────────────────────
        $totalEnrollments = WorkoutEnrollment::count();
        $prevEnrollments  = WorkoutEnrollment::where('created_at', '<', $cutoff)->count();
        $enrollmentGrowth = $this->growthPct($totalEnrollments, $prevEnrollments);

        // ── KPI: Booking Konsultasi ──────────────────────────────────────
        $totalBookings = Booking::count();
        $prevBookings  = Booking::where('created_at', '<', $cutoff)->count();
        $bookingGrowth = $this->growthPct($totalBookings, $prevBookings);

        // ── KPI: Rata-rata Penyelesaian Program ──────────────────────────
        $avgCompletionNow  = round((float) (WorkoutEnrollment::avg('progress_pct') ?? 0), 1);
        $avgCompletionPrev = round((float) (WorkoutEnrollment::where('created_at', '<', $cutoff)->avg('progress_pct') ?? 0), 1);
        $completionDiff    = round($avgCompletionNow - $avgCompletionPrev, 1);

        $kpi = [
            'total_members'      => $totalMembers,
            'prev_members'       => $prevMembers,
            'member_growth'      => $memberGrowth,
            'total_enrollments'  => $totalEnrollments,
            'prev_enrollments'   => $prevEnrollments,
            'enrollment_growth'  => $enrollmentGrowth,
            'total_bookings'     => $totalBookings,
            'prev_bookings'      => $prevBookings,
            'booking_growth'     => $bookingGrowth,
            'avg_completion'     => $avgCompletionNow,
            'avg_completion_prev'=> $avgCompletionPrev,
            'completion_diff'    => $completionDiff,
        ];

        // ── Chart: Pertumbuhan Member (12 bulan terakhir) ────────────────
        $memberGrowthChart = collect(range(11, 0))->map(function ($i) {
            $month = now()->subMonths($i);
            return [
                'label' => $month->locale('id')->isoFormat('MMM'),
                'count' => Member::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        })->toArray();

        // ── Donut: Distribusi Langganan ──────────────────────────────────
        $premiumCount = Member::where('subscription_type', 'premium')->count();
        $freeCount    = $totalMembers - $premiumCount;
        $premiumPct   = $totalMembers > 0 ? round(($premiumCount / $totalMembers) * 100) : 0;

        $subscription = [
            'premium'     => $premiumCount,
            'free'        => $freeCount,
            'premium_pct' => $premiumPct,
        ];

        // ── Leaderboard: Mentor Terbaik (berdasarkan jumlah member unik) ─
        $topMentors = Mentor::withCount('workoutPrograms')
            ->get()
            ->map(fn ($mentor) => (object) [
                'id'                     => $mentor->id,
                'full_name'              => $mentor->full_name,
                'rating'                 => $mentor->rating,
                'workout_programs_count' => $mentor->workout_programs_count,
                'total_members'          => $mentor->totalUniqueMembers(),
            ])
            ->sortByDesc('total_members')
            ->take(4)
            ->values();

        // ── Leaderboard: Program Terpopuler ──────────────────────────────
        $topPrograms = WorkoutProgram::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(4)
            ->get()
            ->map(function ($program) {
                $program->completion_rate = $program->completionRate();
                return $program;
            });

        // ── Booking per Status (menggantikan "tipe sesi" — kolom itu tak ada di skema) ─
        $bookingStatusCounts = Booking::where('created_at', '>=', $cutoff)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $bookingPeriodTotal = (int) $bookingStatusCounts->sum();

        $bookingBreakdown = collect(['pending' => 'Pending', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'])
            ->map(function ($label, $key) use ($bookingStatusCounts, $bookingPeriodTotal) {
                $count = (int) ($bookingStatusCounts[$key] ?? 0);
                return [
                    'key'   => $key,
                    'label' => $label,
                    'count' => $count,
                    'pct'   => $bookingPeriodTotal > 0 ? round(($count / $bookingPeriodTotal) * 100) : 0,
                ];
            })
            ->filter(fn ($row) => $row['count'] > 0)
            ->values();

        // ── Distribusi Enrollment per Level Program ──────────────────────
        $levelCounts = WorkoutEnrollment::join('workout_programs', 'workout_programs.id', '=', 'workout_enrollments.workout_program_id')
            ->selectRaw('workout_programs.level, count(*) as total')
            ->groupBy('workout_programs.level')
            ->pluck('total', 'level');

        $levelTotal = (int) $levelCounts->sum();

        $levelLabels = ['pemula' => 'Pemula', 'menengah' => 'Menengah', 'lanjutan' => 'Lanjutan'];
        $levelBreakdown = collect($levelLabels)->map(function ($label, $key) use ($levelCounts, $levelTotal) {
            $count = (int) ($levelCounts[$key] ?? 0);
            return [
                'key'   => $key,
                'label' => $label,
                'count' => $count,
                'pct'   => $levelTotal > 0 ? round(($count / $levelTotal) * 100) : 0,
            ];
        })->values();

        return view('admin.reports.index', compact(
            'period', 'periodLabel', 'kpi', 'memberGrowthChart', 'subscription',
            'topMentors', 'topPrograms', 'bookingBreakdown', 'bookingPeriodTotal',
            'levelBreakdown'
        ));
    }

    /**
     * Persentase pertumbuhan dari $prev ke $current, dibulatkan 1 desimal.
     * Jika $prev = 0, anggap 100% kalau ada data baru, atau 0% jika tidak ada sama sekali.
     */
    private function growthPct(int $current, int $prev): float
    {
        if ($prev > 0) {
            return round((($current - $prev) / $prev) * 100, 1);
        }

        return $current > 0 ? 100.0 : 0.0;
    }
}
