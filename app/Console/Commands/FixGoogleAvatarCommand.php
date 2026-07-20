<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Mentor;
use Illuminate\Console\Command;

// ══════════════════════════════════════════════════════════════════
//  Artisan Command: fix:google-avatars
//  Penggunaan: php artisan fix:google-avatars
//
//  Perbaikan satu kali untuk akun yang daftar via "Login dengan Google"
//  SEBELUM perbaikan di GoogleAuthController: foto Google mereka
//  tersimpan di users.avatar, tapi tidak pernah disalin ke
//  members.profile_photo_url / mentors.profile_photo_url — yaitu kolom
//  yang benar-benar dipakai halaman admin (Manajemen Member, Manajemen
//  Mentor, Program Latihan, Booking Konsultasi) untuk menampilkan foto.
//  Command ini menyalinnya sekali untuk semua akun yang masih kosong.
// ══════════════════════════════════════════════════════════════════

class FixGoogleAvatarsCommand extends Command
{
    protected $signature   = 'fix:google-avatars';
    protected $description = 'Salin foto Google (users.avatar) ke profile_photo_url untuk member/mentor yang belum punya foto';

    public function handle(): int
    {
        $members = Member::whereNull('profile_photo_url')
            ->whereHas('user', fn ($u) => $u->whereNotNull('google_id')->whereNotNull('avatar'))
            ->with('user')
            ->get();

        foreach ($members as $member) {
            $member->update(['profile_photo_url' => $member->user->avatar]);
        }

        $mentors = Mentor::whereNull('profile_photo_url')
            ->whereHas('user', fn ($u) => $u->whereNotNull('google_id')->whereNotNull('avatar'))
            ->with('user')
            ->get();

        foreach ($mentors as $mentor) {
            $mentor->update(['profile_photo_url' => $mentor->user->avatar]);
        }

        $this->info("Member diperbaiki : {$members->count()}");
        $this->info("Mentor diperbaiki : {$mentors->count()}");

        return self::SUCCESS;
    }
}
