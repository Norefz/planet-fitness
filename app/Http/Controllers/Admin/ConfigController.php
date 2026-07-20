<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SuperAdmin;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ConfigController extends Controller
{
    /**
     * Konfigurasi Sistem — pengaturan platform, registrasi, booking,
     * hak akses per role, dan info akun admin. Nilai diambil dari
     * SystemSetting (default digabung dengan yang tersimpan di DB).
     */
    public function index(): View
    {
        $settings = SystemSetting::current();

        $admins = SuperAdmin::with('user')->orderByDesc('is_head')->orderBy('full_name')->get();

        // Hanya Super Admin utama (is_head) yang boleh menambah admin baru.
        $isHeadAdmin = (bool) (auth('admin')->user()?->superAdmin?->is_head);

        $systemInfo = [
            'app_version' => config('app.version', 'v1.0.0'),
            'framework'   => 'Laravel ' . app()->version(),
            'database'    => strtoupper(config('database.default')),
            'last_update' => $this->lastMigrationDate(),
            'total_logs'  => AuditLog::count(),
        ];

        return view('admin.config.index', compact('settings', 'admins', 'systemInfo', 'isHeadAdmin'));
    }

    /**
     * Tambah akun admin baru (regular admin, bukan Super Admin utama).
     * Hanya boleh dilakukan oleh Super Admin utama (is_head = true).
     */
    public function storeAdmin(Request $request): RedirectResponse
    {
        $currentAdmin = auth('admin')->user()?->superAdmin;

        abort_unless($currentAdmin?->is_head, 403, 'Hanya Super Admin utama yang dapat menambah akun admin.');

        $validated = $request->validate([
            'full_name'   => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'title'       => ['nullable', 'string', 'max:100'],
            'employee_id' => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name'      => $validated['full_name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'super_admin',
            'is_active' => true,
        ]);

        SuperAdmin::create([
            'user_id'     => $user->id,
            'full_name'   => $validated['full_name'],
            'title'       => $validated['title'] ?? 'Admin',
            'employee_id' => $validated['employee_id'] ?? null,
            'is_head'     => false,
        ]);

        AuditLog::record(
            action:  'admin_created',
            details: "Admin baru ditambahkan: {$validated['full_name']} ({$validated['email']}).",
            targetTable: 'super_admins',
            targetId: $user->id,
        );

        return redirect()->route('admin.config')->with('success', "Admin '{$validated['full_name']}' berhasil ditambahkan.");
    }

    /**
     * Simpan perubahan pengaturan. Hanya field yang benar-benar berubah
     * yang dicatat di detail audit log supaya tetap ringkas dan berguna.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform_name'                => ['required', 'string', 'max:100'],
            'support_email'                 => ['required', 'email', 'max:150'],
            'default_timezone'              => ['required', 'string', 'max:50'],
            'min_password_length'           => ['required', 'integer', 'min:6', 'max:32'],
            'booking_max_active_per_member' => ['required', 'integer', 'min:1', 'max:20'],
            'booking_default_duration'      => ['required', 'integer', 'min:15', 'max:180'],
            'booking_cancellation_deadline' => ['required', 'integer', 'min:1', 'max:72'],
        ]);

        // Toggle switches: hanya terkirim di request kalau sedang "on".
        $toggles = [
            'maintenance_mode',
            'member_registration_open',
            'mentor_registration_open',
            'email_verification_required',
            'google_login_enabled',
            'booking_free',
            'booking_auto_confirm',
        ];
        foreach ($toggles as $toggle) {
            $validated[$toggle] = $request->boolean($toggle);
        }

        $before = SystemSetting::current();
        SystemSetting::setMany($validated);

        $changed = [];
        foreach ($validated as $key => $value) {
            $old = $before[$key] ?? null;
            if ($old != $value) {
                $changed[] = $key;
            }
        }

        AuditLog::record(
            action:  'system_config_updated',
            details: $changed
                ? 'Pengaturan diubah: ' . implode(', ', $changed) . '.'
                : 'Konfigurasi disimpan tanpa ada nilai yang berubah.',
            targetTable: 'system_settings',
        );

        return redirect()->route('admin.config')->with('success', 'Konfigurasi berhasil disimpan.');
    }

    /**
     * Zona Berbahaya: hapus seluruh riwayat log aktivitas.
     */
    public function clearLogs(): RedirectResponse
    {
        $count = AuditLog::count();
        AuditLog::query()->delete();

        AuditLog::record(
            action:  'audit_log_purged',
            details: "Seluruh log aktivitas ({$count} baris) dihapus permanen oleh admin.",
            targetTable: 'audit_logs',
        );

        return redirect()->route('admin.config')->with('success', "{$count} log aktivitas berhasil dihapus.");
    }

    /**
     * Zona Berbahaya: kembalikan seluruh pengaturan ke nilai default.
     */
    public function resetDefaults(): RedirectResponse
    {
        SystemSetting::resetToDefaults();

        AuditLog::record(
            action:  'system_config_reset',
            details: 'Seluruh konfigurasi sistem dikembalikan ke nilai default.',
            targetTable: 'system_settings',
        );

        return redirect()->route('admin.config')->with('success', 'Konfigurasi dikembalikan ke default.');
    }

    private function lastMigrationDate(): string
    {
        if (! Schema::hasTable('migrations')) {
            return '—';
        }

        $latest = DB::table('migrations')->orderByDesc('id')->value('migration');

        if (! $latest || ! preg_match('/^(\d{4})_(\d{2})_(\d{2})/', $latest, $m)) {
            return '—';
        }

        return \Illuminate\Support\Carbon::createFromDate((int) $m[1], (int) $m[2], (int) $m[3])
            ->locale('id')->isoFormat('D MMM YYYY');
    }
}
