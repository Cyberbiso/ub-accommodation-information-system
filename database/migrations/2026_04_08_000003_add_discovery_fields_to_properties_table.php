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
            if (!Schema::hasColumn('properties', 'available_units')) {
                $table->unsignedInteger('available_units')->default(1)->after('bathrooms');
            }
            if (!Schema::hasColumn('properties', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->nullable()->after('monthly_rent');
            }
            if (!Schema::hasColumn('properties', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('distance_to_campus_km');
            }
            if (!Schema::hasColumn('properties', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('properties', 'transport_routes')) {
                $table->json('transport_routes')->nullable()->after('amenities');
            }
            if (!Schema::hasColumn('properties', 'nearby_amenities')) {
                $table->json('nearby_amenities')->nullable()->after('transport_routes');
            }
            if (!Schema::hasColumn('properties', 'navigation_notes')) {
                $table->text('navigation_notes')->nullable()->after('nearby_amenities');
            }
            if (!Schema::hasColumn('properties', 'listed_at')) {
                $table->timestamp('listed_at')->nullable()->after('is_approved');
            }
        });

        DB::table('properties')
            ->whereNull('listed_at')
            ->update([
                'listed_at' => DB::raw('created_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $columns = [
                'available_units',
                'deposit_amount',
                'latitude',
                'longitude',
                'transport_routes',
                'nearby_amenities',
                'navigation_notes',
                'listed_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('properties', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
