<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMentorProfileComplete
{
    /**
     * Memastikan Mentor sudah mengisi data profil penting (Onboarding Google)
     * sebelum diizinkan mengakses dashboard utama dan fitur lainnya.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Jika user belum login, lewati saja (biarkan middleware 'auth' atau 'guest' yang menangani)
        if (!Auth::check()) {
            return $next($request);
        }

        /** @var User $user */
        $user = Auth::user();

        // 2. Periksa apakah user yang sedang masuk adalah Mentor
        if ($user->role === 'mentor') {
            // mentorProfile() akan membuat baris Mentor kosong jika belum ada
            $mentor = $user->mentorProfile();

            // 3. Kalau profil belum lengkap (bio/spesialisasi kosong), paksa ke
            //    form onboarding — kecuali request ini memang menuju form itu
            //    sendiri, supaya tidak terjadi redirect loop.
            $isProfileComplete = !empty($mentor->bio) && !empty($mentor->specialization);

            if (!$isProfileComplete && !$request->routeIs('mentor.complete-profile', 'mentor.complete-profile.submit', 'mentor.logout')) {
                return redirect()->route('mentor.complete-profile');
            }
        }

        return $next($request);
    }
}
