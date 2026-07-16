<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('member_id')->constrained('members')->cascadeOnDelete();

            $table->string('food_name');
            $table->enum('category', ['breakfast', 'lunch', 'dinner', 'snack']);

            // Hari kalender yang "memiliki" entri ini — dipisah dari created_at supaya
            // member bisa mencatat/melihat log untuk tanggal yang berbeda dari hari ini.
            $table->date('log_date');

            $table->unsignedSmallInteger('calories')->default(0);
            $table->unsignedSmallInteger('carbs_g')->default(0);
            $table->unsignedSmallInteger('protein_g')->default(0);
            $table->unsignedSmallInteger('fat_g')->default(0);

            $table->timestamps();

            // Query paling umum: "semua log milik member X pada tanggal Y" (lihat SAD 5.3 —
            // MealLog.php, indexing pada user_id & log_date).
            $table->index(['member_id', 'log_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_logs');
    }
};
