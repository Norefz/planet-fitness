<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Member;
use App\Models\Mentor;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder demo untuk menguji halaman web Mentor (dashboard, CRUD program
 * latihan, dan manajemen konsultasi) tanpa harus menunggu modul Member
 * selesai dikembangkan oleh anggota tim lain.
 *
 * Jalankan dengan: php artisan db:seed --class=MentorDemoSeeder
 */
class MentorDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Akun mentor demo (langsung aktif & terverifikasi) ─────────────
        $mentorUser = User::firstOrCreate(
            ['email' => 'mentor@planetfitness.test'],
            [
                'name'      => 'Rini Andini',
                'password'  => Hash::make('password'),
                'role'      => 'mentor',
                'is_active' => true,
            ]
        );

        $mentor = Mentor::firstOrCreate(
            ['user_id' => $mentorUser->id],
            [
                'full_name'      => 'Rini Andini',
                'bio'            => 'Pelatih kebugaran dengan fokus penurunan berat badan dan gaya hidup aktif.',
                'certification'  => 'ACE Certified Personal Trainer',
                'specialization' => 'Pelatih Kebugaran & Penurunan Berat Badan',
                'rating'         => 4.9,
                'is_verified'    => true,
            ]
        );

        // ─── Beberapa akun member demo untuk booking ────────────────────────
        $memberNames = ['Riko Dwi Saputra', 'Anita Nuraini', 'Bezaliel Hosana', 'Nathaniel Putra'];
        $members = collect($memberNames)->map(function ($name, $i) {
            $email = 'member' . ($i + 1) . '@planetfitness.test';
            $user = User::firstOrCreate(['email' => $email], [
                'name'      => $name,
                'password'  => Hash::make('password'),
                'role'      => 'member',
                'is_active' => true,
            ]);

            return Member::firstOrCreate(
                ['user_id' => $user->id],
                ['full_name' => $name]
            );
        });

        // ─── Program latihan contoh ──────────────────────────────────────────
        $programs = [
            ['title' => 'Full Body Fat Burn', 'category' => 'Fat Burn', 'level' => 'menengah', 'status' => 'published', 'duration_weeks' => 6, 'sessions_per_week' => 4, 'sets' => 4, 'reps' => 15],
            ['title' => 'Hypertrophy Strength Builder', 'category' => 'Kekuatan', 'level' => 'lanjutan', 'status' => 'published', 'duration_weeks' => 8, 'sessions_per_week' => 5, 'sets' => 5, 'reps' => 8],
            ['title' => 'Core Stability 21 Hari', 'category' => 'Core', 'level' => 'pemula', 'status' => 'draft', 'duration_weeks' => 3, 'sessions_per_week' => 3, 'sets' => 3, 'reps' => 20],
        ];

        foreach ($programs as $p) {
            WorkoutProgram::firstOrCreate(
                ['mentor_id' => $mentor->id, 'title' => $p['title']],
                array_merge($p, [
                    'description'  => 'Program latihan terstruktur yang disusun oleh ' . $mentor->full_name . ' untuk membantu member mencapai target kebugaran mereka.',
                    'published_at' => $p['status'] === 'published' ? now()->subDays(rand(1, 20)) : null,
                ])
            );
        }

        // ─── Booking contoh: pending, confirmed, completed ──────────────────
        Booking::firstOrCreate(
            ['mentor_id' => $mentor->id, 'member_id' => $members[0]->id, 'scheduled_at' => now()->addDays(2)->setTime(16, 0)],
            ['topic' => 'Evaluasi Program Kekuatan', 'status' => 'pending']
        );

        Booking::firstOrCreate(
            ['mentor_id' => $mentor->id, 'member_id' => $members[1]->id, 'scheduled_at' => now()->addDays(1)->setTime(9, 0)],
            ['topic' => 'Konsultasi Penurunan Berat Badan', 'status' => 'pending']
        );

        Booking::firstOrCreate(
            ['mentor_id' => $mentor->id, 'member_id' => $members[2]->id, 'scheduled_at' => now()->addDays(3)->setTime(13, 0)],
            ['topic' => 'Konsultasi Rutin', 'status' => 'confirmed', 'meeting_url' => 'https://zoom.us/j/1234567890']
        );

        Booking::firstOrCreate(
            ['mentor_id' => $mentor->id, 'member_id' => $members[3]->id, 'scheduled_at' => now()->subDays(5)->setTime(10, 0)],
            ['topic' => 'Konsultasi Rencana Gizi', 'status' => 'completed', 'mentor_notes' => 'Progres baik, lanjutkan program minggu depan.']
        );

        $this->command?->info('Akun mentor demo: mentor@planetfitness.test / password');
    }
}
