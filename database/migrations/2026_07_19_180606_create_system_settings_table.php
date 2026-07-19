<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel key-value untuk Konfigurasi Sistem (halaman admin/config).
     * Hanya baris yang pernah diubah dari default yang disimpan di sini;
     * key yang belum ada akan jatuh ke default bawaan di App\Models\SystemSetting.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
