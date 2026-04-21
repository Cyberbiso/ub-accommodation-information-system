<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_bookings', function (Blueprint $table) {
            $table->text('landlord_rejection_note')->nullable()->after('special_requests');
            $table->timestamp('landlord_reviewed_at')->nullable()->after('landlord_rejection_note');
        });

        // Migrate existing pending_payment bookings that have no payment completed yet
        DB::table('property_bookings')
            ->where('status', 'pending_payment')
            ->update(['status' => 'pending_landlord_review']);
    }

    public function down(): void
    {
        Schema::table('property_bookings', function (Blueprint $table) {
            $table->dropColumn(['landlord_rejection_note', 'landlord_reviewed_at']);
        });

        DB::table('property_bookings')
            ->where('status', 'pending_landlord_review')
            ->update(['status' => 'pending_payment']);
    }
};
