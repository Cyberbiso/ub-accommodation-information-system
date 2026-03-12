<?php
/**
 * MIGRATION: Create Applications Table
 * 
 * This table stores student applications for on-campus accommodation.
 * Tracks the entire application lifecycle from submission to approval.
 * 
 * @package Database\Migrations
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration - create the applications table
     */
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            // Primary key
            $table->id();
            
            // Foreign Keys
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('The student submitting this application');
                  
            $table->foreignId('accommodation_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('The accommodation being applied for');
            
            // Application Status
            $table->enum('status', [
                'pending',      // Submitted, waiting for review
                'approved',      // Approved by welfare officer
                'rejected',      // Rejected with reason
                'waitlisted'     // On waiting list
            ])->default('pending');
            
            // Application Details
            $table->date('preferred_move_in_date');
            $table->integer('duration_months')->default(12);
            $table->text('special_requirements')->nullable()
                  ->comment('Disabilities, medical conditions, preferences');
            
            // Processing Information
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('processed_by')
                  ->nullable()
                  ->constrained('users')
                  ->comment('Welfare officer who processed this application');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('status');
            $table->index('student_id');
            $table->index('accommodation_id');
            $table->index('preferred_move_in_date');
        });
    }

    /**
     * Reverse the migration - drop the applications table
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};