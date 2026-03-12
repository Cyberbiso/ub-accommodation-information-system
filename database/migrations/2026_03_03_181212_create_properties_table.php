<?php
/**
 * MIGRATION: Create Properties Table
 * 
 * This table stores off-campus property listings from landlords.
 * Each record represents a property available for rent.
 * 
 * @package Database\Migrations
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration - create the properties table
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            // Primary key
            $table->id();
            
            // Foreign key to landlord
            $table->foreignId('landlord_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('The landlord who owns this property');
            
            // Basic Information
            $table->string('title');                    // Headline/title of listing
            $table->text('description');                 // Full description
            $table->string('address');                   // Street address
            $table->string('city');                       // City
            $table->string('postal_code')->nullable();   // Postal/ZIP code
            
            // Financial
            $table->decimal('monthly_rent', 10, 2);      // Monthly rent amount
            
            // Property Specifications
            $table->enum('type', ['apartment', 'house', 'shared', 'studio']);
            $table->integer('bedrooms')->default(1);
            $table->integer('bathrooms')->default(1);
            $table->float('distance_to_campus_km')->nullable()
                  ->comment('Distance from university in kilometers');
            
            // Features and Media
            $table->json('amenities')->nullable();       // JSON array of amenities
            $table->json('photos')->nullable();          // JSON array of photo paths
            
            // Status Flags
            $table->boolean('is_available')->default(true);
            $table->boolean('is_approved')->default(false)
                  ->comment('Admin approval required before listing is visible');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for searching/filtering
            $table->index('city');
            $table->index('type');
            $table->index('monthly_rent');
            $table->index('is_approved');
            $table->index('is_available');
            $table->index(['city', 'type', 'monthly_rent']); // Composite index for common searches
        });
    }

    /**
     * Reverse the migration - drop the properties table
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};