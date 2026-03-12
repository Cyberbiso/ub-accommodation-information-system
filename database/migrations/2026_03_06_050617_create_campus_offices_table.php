<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores information about campus offices and their locations
     */
    public function up(): void
    {
        Schema::create('campus_offices', function (Blueprint $table) {
            $table->id();
            $table->string('office_name'); // e.g., "International Student Office"
            $table->string('building'); // e.g., "Block 240"
            $table->string('room_number')->nullable(); // e.g., "Room 105"
            $table->text('description')->nullable(); // What services they offer
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('hours')->nullable(); // e.g., "Mon-Fri 9am-4pm"
            $table->string('map_location')->nullable(); // Link to campus map
            $table->string('category'); // "academic", "administrative", "student_services", "health"
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_offices');
    }
};