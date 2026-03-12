<?php
// database/migrations/2026_03_12_000001_fix_complete_system_schema.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix applications table
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'application_reference')) {
                $table->string('application_reference')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('applications', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('special_requirements');
            }
            if (!Schema::hasColumn('applications', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('applications', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_relationship');
            }
            if (!Schema::hasColumn('applications', 'documents')) {
                $table->json('documents')->nullable()->after('disability_notes');
            }
        });

        // Fix student_documents table
        Schema::table('student_documents', function (Blueprint $table) {
            if (Schema::hasColumn('student_documents', 'file_path') && !Schema::hasColumn('student_documents', 'path')) {
                $table->renameColumn('file_path', 'path');
            }
        });

        // Fix properties table for JSON columns
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'amenities') && !Schema::hasColumn('properties', 'amenities')) {
                // Already exists, ensure it's JSON type
                $table->json('amenities')->nullable()->change();
            }
            if (Schema::hasColumn('properties', 'photos') && !Schema::hasColumn('properties', 'photos')) {
                $table->json('photos')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // Reverse changes if needed
        Schema::table('applications', function (Blueprint $table) {
            $columns = ['application_reference', 'emergency_contact_name', 
                       'emergency_contact_relationship', 'emergency_contact_phone', 'documents'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};