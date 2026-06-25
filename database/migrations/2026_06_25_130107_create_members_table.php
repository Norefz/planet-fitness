<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->uuid('id')->primary(); // PK pakai UUID
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete(); // FK ke users

            $table->string('full_name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->float('height_cm')->nullable();
            $table->float('weight_kg')->nullable();
            $table->string('phone')->nullable();
            $table->text('profile_photo_url')->nullable();
            $table->enum('subscription_type', ['free', 'premium'])->default('free');
            $table->timestamp('subscription_expires_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
