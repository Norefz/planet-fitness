<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_exercises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workout_program_id')->constrained('workout_programs')->cascadeOnDelete();

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();

            // Rep-based (mis. 3 set x 12 repetisi) ATAU time-based (mis. 3 set x 45 detik) —
            // isi salah satu dari reps/duration_seconds sesuai jenis latihannya.
            $table->unsignedSmallInteger('sets')->nullable();
            $table->unsignedSmallInteger('reps')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedSmallInteger('rest_seconds')->nullable();

            // Urutan tampil dalam program (bisa diubah lewat tombol naik/turun, tanpa drag-and-drop).
            $table->unsignedSmallInteger('order_index')->default(0);

            $table->timestamps();

            $table->index(['workout_program_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};
