<?php
/**
 * MIGRATION: Create Payments Table
 * 
 * This table tracks all financial transactions in the system.
 * Uses polymorphic relationship to link to either applications or viewing requests.
 * 
 * @package Database\Migrations
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration - create the payments table
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            // Primary key
            $table->id();
            
            // Foreign key to student making payment
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Student making the payment');
            
            // Polymorphic relationship
            // This allows payment to belong to either an Application OR a ViewingRequest
            $table->morphs('payable'); // Creates payable_id and payable_type columns
            
            // Payment Details
            $table->decimal('amount', 10, 2);              // Payment amount
            $table->enum('type', [
                'application_fee',  // Fee for on-campus application
                'deposit',           // Deposit for approved accommodation
                'rent',              // Monthly rent payment
                'viewing_fee'        // Fee for property viewing (if applicable)
            ])->default('rent');
            
            $table->enum('status', [
                'pending',    // Payment initiated but not completed
                'completed',  // Payment successful
                'failed',     // Payment failed
                'refunded'    // Payment refunded
            ])->default('pending');
            
            // Transaction Information
            $table->string('payment_method')->nullable()   // card, bank_transfer, cash
                  ->comment('Method used for payment');
            $table->string('transaction_id')->nullable()   // External reference
                  ->comment('Reference from payment gateway');
            $table->json('payment_details')->nullable()    // Additional payment data
                  ->comment('JSON containing gateway response, receipt data, etc.');
            
            // Timestamps
            $table->timestamp('paid_at')->nullable()       // When payment was completed
                  ->comment('Date/time payment was completed');
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('transaction_id');
            $table->index('paid_at');
            $table->index(['student_id', 'status']);       // Student viewing their payments
        });
    }

    /**
     * Reverse the migration - drop the payments table
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};