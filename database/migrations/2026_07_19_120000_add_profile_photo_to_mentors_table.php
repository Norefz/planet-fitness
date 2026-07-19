<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mentors', function (Blueprint $table) {
            // Sama seperti members: profile_photo_url dipakai untuk secure_url
            // Cloudinary, profile_photo_public_id disimpan agar foto lama bisa
            // dihapus dari Cloudinary saat diganti/dihapus.
            $table->text('profile_photo_url')->nullable()->after('specialization');
            $table->string('profile_photo_public_id')->nullable()->after('profile_photo_url');
        });
    }

    public function down(): void
    {
        Schema::table('mentors', function (Blueprint $table) {
            $table->dropColumn(['profile_photo_url', 'profile_photo_public_id']);
        });
    }
};
