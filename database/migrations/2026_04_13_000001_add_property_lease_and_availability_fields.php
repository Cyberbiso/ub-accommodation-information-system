<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'available_from')) {
                $table->date('available_from')->nullable()->after('available_units');
            }
            if (!Schema::hasColumn('properties', 'lease_agreement_path')) {
                $table->string('lease_agreement_path')->nullable()->after('photos');
            }
            if (!Schema::hasColumn('properties', 'lease_agreement_original_name')) {
                $table->string('lease_agreement_original_name')->nullable()->after('lease_agreement_path');
            }
            if (!Schema::hasColumn('properties', 'lease_agreement_uploaded_at')) {
                $table->timestamp('lease_agreement_uploaded_at')->nullable()->after('lease_agreement_original_name');
            }
        });

        if (Schema::hasColumn('properties', 'available_from')) {
            DB::table('properties')
                ->whereNull('available_from')
                ->update([
                    'available_from' => DB::raw('COALESCE(DATE(listed_at), DATE(created_at), CURRENT_DATE)'),
                ]);
        }

        if (Schema::hasColumn('properties', 'lease_agreement_path') && Schema::hasColumn('properties', 'lease_agreement_uploaded_at')) {
            DB::table('properties')
                ->whereNotNull('lease_agreement_path')
                ->whereNull('lease_agreement_uploaded_at')
                ->update([
                    'lease_agreement_uploaded_at' => DB::raw('created_at'),
                ]);
        }

        Schema::table('property_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('property_bookings', 'signed_lease_path')) {
                $table->string('signed_lease_path')->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('property_bookings', 'signed_lease_original_name')) {
                $table->string('signed_lease_original_name')->nullable()->after('signed_lease_path');
            }
            if (!Schema::hasColumn('property_bookings', 'signed_lease_submitted_at')) {
                $table->timestamp('signed_lease_submitted_at')->nullable()->after('signed_lease_original_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_bookings', function (Blueprint $table) {
            $columns = [
                'signed_lease_path',
                'signed_lease_original_name',
                'signed_lease_submitted_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('property_bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('properties', function (Blueprint $table) {
            $columns = [
                'available_from',
                'lease_agreement_path',
                'lease_agreement_original_name',
                'lease_agreement_uploaded_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('properties', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
