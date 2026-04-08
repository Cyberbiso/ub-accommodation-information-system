<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('landlord_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending_payment');
            $table->date('move_in_date');
            $table->unsignedInteger('lease_months')->default(12);
            $table->unsignedInteger('occupants')->default(1);
            $table->text('special_requests')->nullable();
            $table->decimal('quoted_rent', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['property_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_bookings');
    }
};
