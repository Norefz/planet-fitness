<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Member;
use App\Models\Mentor;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Search the main records that an admin can manage from one place.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $members = collect();
        $mentors = collect();
        $programs = collect();
        $bookings = collect();

        if ($q !== '') {
            $members = Member::with('user')
                ->where(fn ($query) => $query
                    ->where('full_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($user) => $user->where('email', 'like', "%{$q}%")))
                ->limit(5)
                ->get();

            $mentors = Mentor::with('user')
                ->where(fn ($query) => $query
                    ->where('full_name', 'like', "%{$q}%")
                    ->orWhere('specialization', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($user) => $user->where('email', 'like', "%{$q}%")))
                ->limit(5)
                ->get();

            $programs = WorkoutProgram::with('mentor')
                ->where(fn ($query) => $query
                    ->where('title', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhereHas('mentor', fn ($mentor) => $mentor->where('full_name', 'like', "%{$q}%")))
                ->limit(5)
                ->get();

            $bookings = Booking::with(['member', 'mentor'])
                ->where(fn ($query) => $query
                    ->where('topic', 'like', "%{$q}%")
                    ->orWhereHas('member', fn ($member) => $member->where('full_name', 'like', "%{$q}%"))
                    ->orWhereHas('mentor', fn ($mentor) => $mentor->where('full_name', 'like', "%{$q}%")))
                ->latest('scheduled_at')
                ->limit(5)
                ->get();
        }

        return view('admin.search.index', compact('q', 'members', 'mentors', 'programs', 'bookings'));
    }
}
