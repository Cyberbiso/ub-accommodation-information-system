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
        Schema::table('users', function (Blueprint $table) {
            // Student profile fields
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('users', 'preferences')) {
                $table->json('preferences')->nullable()->after('emergency_contact_phone');
            }
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('preferences');
            }
            
            // Document tracking fields (legacy - will be phased out but kept for compatibility)
            if (!Schema::hasColumn('users', 'document_status')) {
                $table->enum('document_status', ['pending', 'verified', 'rejected'])->default('pending')->after('role');
            }
            if (!Schema::hasColumn('users', 'documents_verified_at')) {
                $table->timestamp('documents_verified_at')->nullable()->after('document_status');
            }
            if (!Schema::hasColumn('users', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->constrained('users')->after('documents_verified_at');
            }
            if (!Schema::hasColumn('users', 'verification_notes')) {
                $table->text('verification_notes')->nullable()->after('verified_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'student_id', 'phone', 'emergency_contact_name', 'emergency_contact_phone',
                'preferences', 'profile_photo', 'document_status', 'documents_verified_at',
                'verified_by', 'verification_notes'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};