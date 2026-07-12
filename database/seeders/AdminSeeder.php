<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// ══════════════════════════════════════════════════════════════════
//  AdminSeeder
//  Jalankan: php artisan db:seed --class=AdminSeeder
//
//  Membuat 1 akun super admin awal. Ganti email & password
//  sebelum dijalankan di production!
// ══════════════════════════════════════════════════════════════════

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'email'       => 'admin@planetfitness.id',
                'password'    => 'Admin@12345',       // ← GANTI sebelum production
                'full_name'   => 'Wahyu Saputra',
                'title'       => 'Super Admin Utama',
                'employee_id' => 'ADM-001',
                'is_head'     => true,
            ],
            [
                'email'       => 'konten@planetfitness.id',
                'password'    => 'Konten@12345',       // ← GANTI sebelum production
                'full_name'   => 'Lestari Putri',
                'title'       => 'Admin Konten',
                'employee_id' => 'ADM-002',
                'is_head'     => false,
            ],
        ];

        foreach ($admins as $data) {
            // Buat atau update user
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'id'           => Str::uuid(),
                    'name'         => $data['full_name'],
                    'password'     => Hash::make($data['password']),
                    'role'         => 'super_admin',
                    'is_active'    => true,
                ]
            );

            // Buat profil super_admin jika belum ada
            if (! SuperAdmin::where('user_id', $user->id)->exists()) {
                SuperAdmin::create([
                    'id'          => Str::uuid(),
                    'user_id'     => $user->id,
                    'full_name'   => $data['full_name'],
                    'title'       => $data['title'],
                    'employee_id' => $data['employee_id'],
                    'is_head'     => $data['is_head'],
                ]);
            }

            $this->command->info("✓ Admin dibuat: {$data['email']}");
        }

        $this->command->newLine();
        $this->command->warn('⚠  Jangan lupa ganti password default sebelum deploy ke production!');
    }
}
