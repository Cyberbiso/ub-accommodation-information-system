<?php
/**
 * MIGRATION: Create Viewing Requests Table
 * 
 * This table stores requests from students to view off-campus properties.
 * Landlords can approve/reject and schedule viewing times.
 * 
 * @package Database\Migrations
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration - create the viewing_requests table
     */
    public function up(): void
    {
        Schema::create('viewing_requests', function (Blueprint $table) {
            // Primary key
            $table->id();
            
            // Foreign Keys
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Student requesting the viewing');
                  
            $table->foreignId('property_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('Property to be viewed');
                  
            $table->foreignId('landlord_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Landlord who owns the property');
            
            // Request Details
            $table->dateTime('preferred_date');           // When student wants to view
            $table->text('message')->nullable()           // Optional message from student
                  ->comment('Additional information from student');
            
            // Status and Response
            $table->enum('status', [
                'pending',    // Awaiting landlord response
                'approved',   // Landlord approved, date scheduled
                'rejected',   // Landlord rejected
                'completed'   // Viewing took place
            ])->default('pending');
            
            $table->dateTime('scheduled_date')->nullable() // Confirmed viewing time
                  ->comment('Actual scheduled viewing time (if approved)');
                  
            $table->text('landlord_response')->nullable()  // Response message
                  ->comment('Landlord\'s response message');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('preferred_date');
            $table->index(['landlord_id', 'status']);     // Common query: landlord viewing pending requests
            $table->index(['student_id', 'status']);      // Common query: student checking request status
        });
    }

    /**
     * Reverse the migration - drop the viewing_requests table
     */
    public function down(): void
    {
        Schema::dropIfExists('viewing_requests');
    }
};