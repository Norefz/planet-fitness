<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('member_id')->constrained()->cascadeOnDelete();
            $table->string('order_id', 50)->unique();
            $table->unsignedInteger('amount');
            $table->string('status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();

            $table->index(['member_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
