<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            // Menyimpan Cloudinary public_id agar video lama bisa dihapus dari
            // Cloudinary saat diganti/dihapus (video_url tetap dipakai untuk secure_url-nya).
            $table->string('video_public_id')->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->dropColumn('video_public_id');
        });
    }
};
