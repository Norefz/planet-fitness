<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mentor = Auth::user()->mentor;

        $programs = $mentor->workoutPrograms();
        $bookings = $mentor->bookings();

        $stats = [
            'programs_total'     => (clone $programs)->count(),
            'programs_published' => (clone $programs)->published()->count(),
            'programs_draft'     => (clone $programs)->draft()->count(),
            'bookings_pending'   => (clone $bookings)->pending()->count(),
            'bookings_upcoming'  => (clone $bookings)->upcoming()->count(),
            'avg_progress'       => $mentor->overallAverageProgress(),
            'unique_members'     => $mentor->totalUniqueMembers(),
        ];

        $recentPrograms = $mentor->workoutPrograms()
            ->withCount('enrollments')
            ->with('enrollments:id,workout_program_id,progress_pct,status')
            ->latest()
            ->take(4)
            ->get();

        $pendingBookings = $mentor->bookings()
            ->pending()
            ->with('member')
            ->orderBy('scheduled_at')
            ->take(4)
            ->get();

        $attentionList = $mentor->enrollments()
            ->needsAttention()
            ->with(['member', 'workoutProgram'])
            ->orderBy('last_activity_at')
            ->take(4)
            ->get();

        return view('mentor.dashboard', compact('mentor', 'stats', 'recentPrograms', 'pendingBookings', 'attentionList'));
    }
}
