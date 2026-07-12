<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignUuid('workout_program_id')->constrained('workout_programs')->cascadeOnDelete();

            // Diperbarui oleh sisi Member setiap kali sebuah sesi/latihan diselesaikan
            // (lihat SAD 5.2 — WorkoutController & progress_pct). Modul Mentor hanya membaca nilai ini.
            $table->unsignedTinyInteger('progress_pct')->default(0);
            $table->enum('status', ['active', 'completed'])->default('active');

            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Satu member hanya punya satu baris progres per program.
            $table->unique(['member_id', 'workout_program_id']);
            $table->index(['workout_program_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_enrollments');
    }
};
