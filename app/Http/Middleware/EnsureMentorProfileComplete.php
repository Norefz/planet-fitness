<?php

namespace App\Http\Middleware;

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

        $user = Auth::user();

        // 2. Periksa apakah user yang sedang masuk adalah Mentor
        if ($user->role === 'mentor') {
            $mentor = $user->mentor;

            // 3. INDIKATOR KUNCI: Jika data profil mentor (bio atau spesialisasi) masih kosong
            if (!$mentor || empty($mentor->bio) || empty($mentor->specialization)) {

                // Cek agar tidak terjadi looping error (infinite redirect) saat mengakses halaman form onboarding itu sendiri
                if (!$request->routeIs('mentor.complete-profile') && !$request->routeIs('mentor.complete-profile.submit')) {

                    // Lempar paksa ke halaman pengisian profil mentor
                    return redirect()->route('mentor.complete-profile')
                        ->with('warning', 'Anda wajib melengkapi sertifikasi, spesialisasi, dan bio sebelum melanjutkan.');
                }
            }
        }

        return $next($request);
    }
}
