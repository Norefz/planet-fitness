<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mentor_id')->constrained('mentors')->cascadeOnDelete();

            $table->string('title');
            $table->string('category')->default('Umum'); // Fat Burn, Kekuatan, Core, Kardio, dst.
            $table->enum('level', ['pemula', 'menengah', 'lanjutan'])->default('pemula');
            $table->text('description');

            $table->unsignedSmallInteger('duration_weeks')->nullable();
            $table->unsignedTinyInteger('sessions_per_week')->nullable();
            $table->unsignedSmallInteger('sets')->nullable();
            $table->unsignedSmallInteger('reps')->nullable();

            $table->string('video_url')->nullable(); // endpoint multimedia (mis. dari AWS S3)

            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['mentor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_programs');
    }
};
