<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'has_disability')) {
                $table->boolean('has_disability')->default(false)->after('special_requirements');
            }
            if (!Schema::hasColumn('applications', 'medical_certificate')) {
                $table->string('medical_certificate')->nullable()->after('has_disability');
            }
            if (!Schema::hasColumn('applications', 'medical_status')) {
                $table->enum('medical_status', ['pending', 'verified', 'rejected'])->nullable()->after('medical_certificate');
            }
            if (!Schema::hasColumn('applications', 'disability_notes')) {
                $table->text('disability_notes')->nullable()->after('medical_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $columns = ['has_disability', 'medical_certificate', 'medical_status', 'disability_notes'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};