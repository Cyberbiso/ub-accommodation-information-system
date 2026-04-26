<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Bookings with old pending_payment status that already have a pending payment record
        // → skip the lease step, move directly to approved_awaiting_payment
        $withPayment = DB::table('property_bookings')
            ->where('status', 'pending_payment')
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('payments')
                  ->whereColumn('payments.payable_id', 'property_bookings.id')
                  ->where('payments.payable_type', 'App\\Models\\PropertyBooking')
                  ->where('payments.status', 'pending');
            })
            ->pluck('id');

        if ($withPayment->isNotEmpty()) {
            DB::table('property_bookings')
                ->whereIn('id', $withPayment)
                ->update(['status' => 'approved_awaiting_payment']);
        }

        // Remaining pending_payment bookings (no pending payment record)
        // → move to approved_awaiting_lease so student can sign
        DB::table('property_bookings')
            ->where('status', 'pending_payment')
            ->update(['status' => 'approved_awaiting_lease']);
    }

    public function down(): void
    {
        // Not reversible — pending_payment status is retired
    }
};
