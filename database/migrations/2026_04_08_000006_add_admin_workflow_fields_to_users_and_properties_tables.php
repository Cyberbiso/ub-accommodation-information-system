<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'landlord_verification_reviewed_by')) {
                $table->foreignId('landlord_verification_reviewed_by')->nullable()->after('landlord_verified_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'landlord_verification_reviewed_at')) {
                $table->timestamp('landlord_verification_reviewed_at')->nullable()->after('landlord_verification_reviewed_by');
            }
        });

        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'review_status')) {
                $table->string('review_status')->default('pending')->after('is_approved');
            }
            if (!Schema::hasColumn('properties', 'review_notes')) {
                $table->text('review_notes')->nullable()->after('review_status');
            }
            if (!Schema::hasColumn('properties', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->after('review_notes')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('properties', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });

        DB::table('properties')->update([
            'review_status' => DB::raw("CASE WHEN is_approved = 1 THEN 'approved' ELSE 'pending' END"),
        ]);
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $columns = ['review_status', 'review_notes', 'reviewed_by', 'reviewed_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('properties', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = ['is_active', 'landlord_verification_reviewed_by', 'landlord_verification_reviewed_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
