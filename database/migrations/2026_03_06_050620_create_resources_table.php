<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores general resources and documents for students
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', [
                'document', 
                'link', 
                'faq', 
                'guide', 
                'video', 
                'contact'
            ]);
            $table->string('file_path')->nullable(); // For uploaded documents
            $table->string('external_link')->nullable(); // For external resources
            $table->json('tags')->nullable(); // For searching
            $table->string('category'); // e.g., "academics", "housing", "visa", "health"
            $table->integer('download_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};