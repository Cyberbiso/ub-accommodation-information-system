<?php
/**
 * MIGRATION: Create Accommodations Table
 * 
 * This table stores all on-campus accommodation information.
 * Each record represents a single room or unit in university housing.
 * 
 * @package Database\Migrations
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration - create the accommodations table
     */
    public function up(): void
    {
        Schema::create('accommodations', function (Blueprint $table) {
            // Primary key - unique identifier for each room
            $table->id();
            
            // Basic Information
            $table->string('name');  // e.g., "Block A - Room 101"
            $table->enum('type', ['single', 'shared', 'family'])->default('single');
            
            // Capacity and Occupancy
            $table->integer('capacity')->default(1);      // Maximum number of students
            $table->integer('current_occupancy')->default(0); // Currently assigned students
            
            // Financial
            $table->decimal('monthly_rent', 10, 2);       // Rent amount per month
            
            // Facilities (stored as JSON for flexibility)
            $table->json('facilities')->nullable();       // e.g., ["WiFi", "Bed", "Desk"]
            
            // Location and Status
            $table->boolean('is_available')->default(true);
            $table->string('block')->nullable();           // Building block (A, B, C, etc.)
            $table->integer('floor')->nullable();          // Floor number
            
            // Timestamps for record keeping
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('type');
            $table->index('is_available');
            $table->index('monthly_rent');
        });
    }

    /**
     * Reverse the migration - drop the accommodations table
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};