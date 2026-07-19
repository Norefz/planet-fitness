<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'system_settings.all';

    /**
     * Nilai default seluruh pengaturan platform. Key yang tidak ada di tabel
     * `system_settings` akan jatuh ke sini, jadi halaman Konfigurasi Sistem
     * selalu bisa tampil walau belum pernah disimpan sama sekali.
     */
    public static function defaults(): array
    {
        return [
            // ── Informasi Platform ──────────────────────────────
            'platform_name'                 => 'Planet Fitness',
            'support_email'                 => 'support@planetfitness.id',
            'default_timezone'              => 'Asia/Jakarta',
            'maintenance_mode'               => false,

            // ── Registrasi & Akun ───────────────────────────────
            'member_registration_open'       => true,
            'mentor_registration_open'       => true,
            'email_verification_required'    => true,
            'google_login_enabled'           => true,
            'min_password_length'            => 8,

            // ── Booking & Konsultasi ────────────────────────────
            'booking_free'                   => true,
            'booking_auto_confirm'           => false,
            'booking_max_active_per_member'  => 3,
            'booking_default_duration'       => 60,
            'booking_cancellation_deadline'  => 6,
        ];
    }

    /**
     * Semua pengaturan saat ini (default digabung dengan yang tersimpan),
     * di-cache singkat supaya tidak query berkali-kali dalam satu request.
     */
    public static function current(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            $stored = static::query()->pluck('value', 'key')->all();
            $result = [];

            foreach (static::defaults() as $key => $default) {
                if (! array_key_exists($key, $stored)) {
                    $result[$key] = $default;
                    continue;
                }

                $result[$key] = match (true) {
                    is_bool($default) => filter_var($stored[$key], FILTER_VALIDATE_BOOLEAN),
                    is_int($default)  => (int) $stored[$key],
                    default           => $stored[$key],
                };
            }

            return $result;
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::current()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $stringValue = is_bool($value) ? ($value ? '1' : '0') : (string) $value;

        static::updateOrCreate(['key' => $key], ['value' => $stringValue]);
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Simpan beberapa key sekaligus (dipakai oleh ConfigController::update).
     */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $stringValue = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
            static::updateOrCreate(['key' => $key], ['value' => $stringValue]);
        }
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Hapus semua override tersimpan, sehingga seluruh pengaturan kembali
     * memakai nilai default (dipakai oleh tombol "Reset Konfigurasi ke Default").
     */
    public static function resetToDefaults(): void
    {
        static::query()->delete();
        Cache::forget(self::CACHE_KEY);
    }
}
