<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Jalankan: php artisan migrate

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // ID meeting dari Zoom — dipakai untuk hapus meeting saat dibatalkan
            $table->string('zoom_meeting_id')->nullable()->after('meeting_url');

            // Link host khusus mentor (berbeda dari join_url member)
            $table->text('zoom_start_url')->nullable()->after('zoom_meeting_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['zoom_meeting_id', 'zoom_start_url']);
        });
    }
};;
