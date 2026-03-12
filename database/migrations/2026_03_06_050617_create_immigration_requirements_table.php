<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Stores immigration compliance information for international students
     */
    public function up(): void
    {
        Schema::create('immigration_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Student Visa Requirements"
            $table->text('description');
            $table->enum('category', ['visa', 'passport', 'permits', 'documents', 'deadlines']);
            $table->json('required_documents')->nullable(); // List of documents needed
            $table->text('process_steps')->nullable(); // Step-by-step guide
            $table->string('office_responsible')->nullable(); // Which office handles this
            $table->string('link_to_form')->nullable(); // Link to download forms
            $table->integer('priority')->default(1); // 1 = high, 2 = medium, 3 = low
            $table->date('deadline')->nullable(); // Important deadlines
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immigration_requirements');
    }
};