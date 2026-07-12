<?php

namespace App\Console\Commands;

use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// ══════════════════════════════════════════════════════════════════
//  Artisan Command: admin:create
//  Penggunaan: php artisan admin:create
//
//  Membuat akun super admin baru langsung dari terminal.
//  Tidak ada halaman register admin di web.
// ══════════════════════════════════════════════════════════════════

class CreateAdminCommand extends Command
{
    protected $signature   = 'admin:create';
    protected $description = 'Buat akun super admin baru';

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════╗');
        $this->info('║      Planet Fitness — Admin Creator   ║');
        $this->info('╚══════════════════════════════════════╝');
        $this->info('');

        // ── Kumpulkan input ───────────────────────────────────
        $fullName = $this->ask('Nama lengkap admin');

        $email = $this->ask('Email admin');
        if (User::where('email', $email)->exists()) {
            $this->error("Email '$email' sudah terdaftar!");
            return self::FAILURE;
        }

        $password = $this->secret('Kata sandi (minimal 8 karakter)');
        if (strlen($password) < 8) {
            $this->error('Kata sandi minimal 8 karakter!');
            return self::FAILURE;
        }

        $passwordConfirm = $this->secret('Konfirmasi kata sandi');
        if ($password !== $passwordConfirm) {
            $this->error('Kata sandi tidak cocok!');
            return self::FAILURE;
        }

        $title      = $this->ask('Jabatan (opsional)', 'Admin');
        $employeeId = $this->ask('ID Karyawan (opsional)', null);
        $isHead     = $this->confirm('Tandai sebagai Super Admin utama?', false);

        // ── Konfirmasi ────────────────────────────────────────
        $this->info('');
        $this->table(
            ['Field', 'Value'],
            [
                ['Nama',       $fullName],
                ['Email',      $email],
                ['Jabatan',    $title],
                ['ID Karyawan',$employeeId ?? '-'],
                ['Is Head',    $isHead ? 'Ya' : 'Tidak'],
            ]
        );

        if (! $this->confirm('Buat akun admin dengan data di atas?', true)) {
            $this->warn('Dibatalkan.');
            return self::SUCCESS;
        }

        // ── Buat akun ─────────────────────────────────────────
        $user = User::create([
            'id'        => Str::uuid(),
            'name'      => $fullName,
            'email'     => $email,
            'password'  => Hash::make($password),
            'role'      => 'super_admin',
            'is_active' => true,
        ]);

        SuperAdmin::create([
            'id'          => Str::uuid(),
            'user_id'     => $user->id,
            'full_name'   => $fullName,
            'title'       => $title,
            'employee_id' => $employeeId,
            'is_head'     => $isHead,
        ]);

        $this->info('');
        $this->info("✅ Akun admin berhasil dibuat!");
        $this->info("   Email : $email");
        $this->info("   Login : " . url('/admin/login'));
        $this->info('');
        $this->warn('⚠  Catat email dan password ini. Password tidak bisa dilihat lagi.');

        return self::SUCCESS;
    }
}
