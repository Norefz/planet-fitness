<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMentorProfileComplete
{
    /**
     * Memastikan Mentor sudah mengisi data profil penting
     * sebelum bisa mengakses dashboard utama.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Pastikan dia adalah mentor dan profil mentornya terdaftar
        if ($user && $user->role === 'mentor' && $user->mentor) {

            // INDIKATOR KUNCI: Profil dianggap belum lengkap jika kolom wajib (misal: bio/spesialisasi) kosong
            if (empty($user->mentor->bio) || empty($user->mentor->specialization)) {

                // Jika dia bukan sedang mengakses halaman onboarding, lempar paksa ke form onboarding
                if (!$request->routeIs('mentor.complete-profile') && !$request->routeIs('mentor.complete-profile.submit')) {
                    return redirect()->route('mentor.complete-profile')
                        ->with('warning', 'Anda wajib melengkapi sertifikasi, spesialisasi, dan bio sebelum melanjutkan.');
                }
            }
        }

        return $next($request);
    }
}
