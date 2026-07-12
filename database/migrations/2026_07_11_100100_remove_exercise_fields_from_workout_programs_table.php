<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sebuah program kini bisa memiliki banyak latihan (lihat workout_exercises),
 * masing-masing dengan video, set, dan repetisinya sendiri. Kolom video_url,
 * sets, dan reps di level program jadi berlebihan dan dipindahkan ke sana.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_programs', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'sets', 'reps']);
        });
    }

    public function down(): void
    {
        Schema::table('workout_programs', function (Blueprint $table) {
            $table->string('video_url')->nullable();
            $table->unsignedSmallInteger('sets')->nullable();
            $table->unsignedSmallInteger('reps')->nullable();
        });
    }
};
