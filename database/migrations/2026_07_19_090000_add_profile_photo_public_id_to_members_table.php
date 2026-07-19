<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Menyimpan Cloudinary public_id agar foto profil lama bisa dihapus
            // dari Cloudinary saat diganti/dihapus (profile_photo_url tetap
            // dipakai untuk secure_url-nya).
            $table->string('profile_photo_public_id')->nullable()->after('profile_photo_url');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('profile_photo_public_id');
        });
    }
};
