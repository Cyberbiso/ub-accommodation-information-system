<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores pre-arrival checklist items for students
     */
    public function up(): void
    {
        Schema::create('onboarding_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', [
                'before_arrival', 
                'upon_arrival', 
                'first_week', 
                'ongoing'
            ]);
            $table->integer('estimated_days')->nullable(); // How many days before arrival
            $table->json('subtasks')->nullable(); // JSON array of smaller tasks
            $table->json('resources')->nullable(); // Links to relevant resources
            $table->integer('sort_order')->default(0);
            $table->boolean('is_mandatory')->default(false); // Is this required?
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_checklists');
    }
};