<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Member;
use App\Models\Mentor;
use App\Models\User;
use App\Models\WorkoutEnrollment;
use App\Models\WorkoutExercise;
use App\Models\WorkoutProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder demo untuk menguji halaman web Mentor (dashboard, CRUD program
 * latihan, manajemen konsultasi, dan statistik progres member) tanpa harus
 * menunggu modul Member selesai dikembangkan oleh anggota tim lain.
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

        // ─── Beberapa akun member demo untuk booking & progres program ──────
        $memberNames = [
            'Riko Dwi Saputra', 'Anita Nuraini', 'Bezaliel Hosana', 'Nathaniel Putra',
            'Erlangga Bintang', 'Donny Bernando', 'Salsabila Putri', 'Farrel Ahmad',
        ];
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
        $programDefinitions = [
            'Full Body Fat Burn'          => ['category' => 'Fat Burn', 'level' => 'menengah', 'status' => 'published', 'duration_weeks' => 6, 'sessions_per_week' => 4],
            'Hypertrophy Strength Builder' => ['category' => 'Kekuatan', 'level' => 'lanjutan', 'status' => 'published', 'duration_weeks' => 8, 'sessions_per_week' => 5],
            'Core Stability 21 Hari'       => ['category' => 'Core', 'level' => 'pemula', 'status' => 'draft', 'duration_weeks' => 3, 'sessions_per_week' => 3],
        ];

        $programs = collect($programDefinitions)->map(function ($p, $title) use ($mentor) {
            return WorkoutProgram::firstOrCreate(
                ['mentor_id' => $mentor->id, 'title' => $title],
                array_merge($p, [
                    'title'        => $title,
                    'description'  => 'Program latihan terstruktur yang disusun oleh ' . $mentor->full_name . ' untuk membantu member mencapai target kebugaran mereka.',
                    'published_at' => $p['status'] === 'published' ? now()->subDays(rand(1, 20)) : null,
                ])
            );
        });

        // ─── Beberapa jenis latihan per program — inilah inti dari fitur baru:
        // satu program bisa punya banyak latihan, masing-masing dengan video,
        // set/repetisi (atau durasi), dan istirahatnya sendiri.
        $exerciseDefinitions = [
            'Full Body Fat Burn' => [
                ['name' => 'Jumping Jacks',      'sets' => 3, 'reps' => 20, 'rest_seconds' => 30, 'video_url' => 'https://youtube.com/watch?v=jumping-jacks'],
                ['name' => 'Burpees',            'sets' => 3, 'reps' => 12, 'rest_seconds' => 45, 'video_url' => 'https://youtube.com/watch?v=burpees'],
                ['name' => 'Mountain Climbers',  'sets' => 3, 'duration_seconds' => 30, 'rest_seconds' => 30],
                ['name' => 'High Knees',         'sets' => 3, 'duration_seconds' => 30, 'rest_seconds' => 20],
            ],
            'Hypertrophy Strength Builder' => [
                ['name' => 'Barbell Back Squat', 'sets' => 4, 'reps' => 8,  'rest_seconds' => 90, 'video_url' => 'https://youtube.com/watch?v=back-squat',
                    'description' => 'Jaga punggung tetap netral, turun hingga paha sejajar lantai.'],
                ['name' => 'Bench Press',        'sets' => 4, 'reps' => 8,  'rest_seconds' => 90, 'video_url' => 'https://youtube.com/watch?v=bench-press'],
                ['name' => 'Deadlift',           'sets' => 3, 'reps' => 6,  'rest_seconds' => 120],
                ['name' => 'Bent-over Row',      'sets' => 3, 'reps' => 10, 'rest_seconds' => 60],
            ],
            'Core Stability 21 Hari' => [
                ['name' => 'Plank',              'sets' => 3, 'duration_seconds' => 45, 'rest_seconds' => 30, 'video_url' => 'https://youtube.com/watch?v=plank'],
                ['name' => 'Russian Twist',       'sets' => 3, 'reps' => 20, 'rest_seconds' => 20],
                ['name' => 'Leg Raise',          'sets' => 3, 'reps' => 15, 'rest_seconds' => 30],
            ],
        ];

        foreach ($exerciseDefinitions as $title => $exercises) {
            foreach ($exercises as $i => $ex) {
                WorkoutExercise::firstOrCreate(
                    ['workout_program_id' => $programs[$title]->id, 'name' => $ex['name']],
                    array_merge($ex, ['order_index' => $i + 1])
                );
            }
        }

        // ─── Progres member per program (WorkoutEnrollment) ─────────────────
        // Hanya program yang published yang realistis punya member terdaftar.
        // Campuran sengaja dibuat: ada yang hampir selesai, di tengah jalan,
        // baru mulai, dan yang macet (untuk menguji fitur "Perlu Perhatian").
        $fatBurn = $programs['Full Body Fat Burn'];
        $strength = $programs['Hypertrophy Strength Builder'];

        $enrollments = [
            // [program, member, progress, status, started_days_ago, last_activity_days_ago(null = belum pernah)]
            [$fatBurn,  $members[0], 100, 'completed', 45, 2],
            [$fatBurn,  $members[1],  82, 'active',    30, 1],
            [$fatBurn,  $members[2],  58, 'active',    24, 3],
            [$fatBurn,  $members[3],  35, 'active',    20, 10],   // perlu perhatian: 10 hari tidak aktif
            [$fatBurn,  $members[4],   8, 'active',     6, null], // perlu perhatian: belum pernah aktif sejak daftar
            [$strength, $members[1], 100, 'completed', 50, 5],
            [$strength, $members[5],  90, 'active',    35, 1],
            [$strength, $members[6],  62, 'active',    21, 2],
            [$strength, $members[7],  20, 'active',    14, 15],  // perlu perhatian: 15 hari tidak aktif
        ];

        foreach ($enrollments as [$program, $member, $progress, $status, $startedDaysAgo, $lastActivityDaysAgo]) {
            WorkoutEnrollment::firstOrCreate(
                ['workout_program_id' => $program->id, 'member_id' => $member->id],
                [
                    'progress_pct'     => $progress,
                    'status'           => $status,
                    'started_at'       => now()->subDays($startedDaysAgo),
                    'last_activity_at' => $lastActivityDaysAgo === null ? null : now()->subDays($lastActivityDaysAgo),
                    'completed_at'     => $status === 'completed' ? now()->subDays($lastActivityDaysAgo ?? 0) : null,
                ]
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
