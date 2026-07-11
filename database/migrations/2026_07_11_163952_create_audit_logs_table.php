<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('admin_id')->nullable();          // FK ke super_admins
            $table->foreign('admin_id')
                  ->references('id')->on('super_admins')
                  ->nullOnDelete();

            $table->string('action');                      // member_register, mentor_verify, dll
            $table->string('target_table')->nullable();    // USERS, MENTORS, BOOKINGS, dll
            $table->uuid('target_id')->nullable();         // ID baris yang diubah
            $table->text('details')->nullable();           // Deskripsi human-readable
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('performed_at')->useCurrent();

            $table->index(['admin_id', 'performed_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
