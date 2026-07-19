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
     * Rute yang tetap boleh diakses walau mentor BELUM diverifikasi admin —
     * supaya mentor tetap bisa logout dan melengkapi/memperbaiki profilnya
     * sambil menunggu, tanpa terjebak loop redirect ke halaman itu sendiri.
     */
    private const ALLOWED_WHILE_PENDING = [
        'mentor.logout',
        'mentor.pending-verification',
        'mentor.profile.edit',
        'mentor.profile.update',
        'mentor.profile.photo.destroy',
    ];

    /**
     * Memastikan Mentor sudah mengisi data profil penting (Onboarding Google)
     * DAN sudah diverifikasi/disetujui oleh admin sebelum diizinkan mengakses
     * dashboard utama dan fitur lainnya.
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
            $mentor = $user->mentor()->first();

            if (!$mentor) {
                $mentor = $user->mentorProfile();
            }

            // 3. Mentor yang belum disetujui admin (is_verified = false) tidak boleh
            //    mengakses dashboard, program, booking, atau statistik — kecuali
            //    rute yang memang dikecualikan di atas.
            if (! $mentor->is_verified && ! $request->routeIs(...self::ALLOWED_WHILE_PENDING)) {
                return redirect()->route('mentor.pending-verification');
            }
        }

        return $next($request);
    }
}
