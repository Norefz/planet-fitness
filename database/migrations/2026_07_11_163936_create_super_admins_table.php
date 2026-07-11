<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ══════════════════════════════════════════════════════════
//  Migration: super_admins (sesuai tabel SUPER_ADMINS di ERD)
//  Jalankan: php artisan migrate
// ══════════════════════════════════════════════════════════

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('super_admins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->string('full_name');
            $table->string('title')->nullable();          // Misal: "Admin Utama", "Admin Konten"
            $table->string('employee_id')->nullable();    // ID karyawan internal
            $table->boolean('is_head')->default(false);   // Super admin utama
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('super_admins');
    }
};
