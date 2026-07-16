<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Target nutrisi harian member, dipakai halaman Log Nutrisi untuk menghitung sisa
 * kalori & progres makronutrisi (lihat SAD 6.1 Proses 1 — rekomendasi batas kalori
 * harian). Nilai default merepresentasikan target umum yang belum dipersonalisasi;
 * kalkulasi penuh berbasis BMR/TDEE dari data biometrik adalah pekerjaan
 * onboarding terpisah dan di luar cakupan perubahan ini.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->unsignedSmallInteger('daily_calorie_target')->default(2000)->after('subscription_expires_at');
            $table->unsignedSmallInteger('daily_carbs_target_g')->default(200)->after('daily_calorie_target');
            $table->unsignedSmallInteger('daily_protein_target_g')->default(150)->after('daily_carbs_target_g');
            $table->unsignedSmallInteger('daily_fat_target_g')->default(65)->after('daily_protein_target_g');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'daily_calorie_target',
                'daily_carbs_target_g',
                'daily_protein_target_g',
                'daily_fat_target_g',
            ]);
        });
    }
};
