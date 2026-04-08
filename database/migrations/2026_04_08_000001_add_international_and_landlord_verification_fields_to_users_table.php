<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'student_category')) {
                $table->string('student_category')->default('local')->after('student_id');
            }
            if (!Schema::hasColumn('users', 'nationality')) {
                $table->string('nationality')->nullable()->after('student_category');
            }
            if (!Schema::hasColumn('users', 'country_of_origin')) {
                $table->string('country_of_origin')->nullable()->after('nationality');
            }
            if (!Schema::hasColumn('users', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('country_of_origin');
            }
            if (!Schema::hasColumn('users', 'immigration_status')) {
                $table->string('immigration_status')->nullable()->after('passport_number');
            }
            if (!Schema::hasColumn('users', 'company_registration_number')) {
                $table->string('company_registration_number')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('users', 'tax_identification_number')) {
                $table->string('tax_identification_number')->nullable()->after('company_registration_number');
            }
            if (!Schema::hasColumn('users', 'landlord_verification_status')) {
                $table->string('landlord_verification_status')->default('not_started')->after('verification_notes');
            }
            if (!Schema::hasColumn('users', 'landlord_verification_stage')) {
                $table->string('landlord_verification_stage')->default('company_registration')->after('landlord_verification_status');
            }
            if (!Schema::hasColumn('users', 'landlord_verification_submitted_at')) {
                $table->timestamp('landlord_verification_submitted_at')->nullable()->after('landlord_verification_stage');
            }
            if (!Schema::hasColumn('users', 'landlord_verified_at')) {
                $table->timestamp('landlord_verified_at')->nullable()->after('landlord_verification_submitted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'student_category',
                'nationality',
                'country_of_origin',
                'passport_number',
                'immigration_status',
                'company_registration_number',
                'tax_identification_number',
                'landlord_verification_status',
                'landlord_verification_stage',
                'landlord_verification_submitted_at',
                'landlord_verified_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
