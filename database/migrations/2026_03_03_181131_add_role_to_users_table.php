<?php
/**
 * MIGRATION: Add Role Column to Users Table
 * 
 * This migration adds a 'role' column to the existing users table
 * to support different user types in the system.
 * 
 * @package Database\Migrations
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration - add the role column
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role column after password
            // ENUM ensures only valid roles are stored
            $table->enum('role', [
                'student',      // Students looking for accommodation
                'landlord',      // Property owners listing off-campus housing
                'welfare',       // Welfare officers managing on-campus applications
                'admin'          // System administrators
            ])->default('student')->after('password');
            
            // Add index for faster queries filtering by role
            $table->index('role');
        });
    }

    /**
     * Reverse the migration - remove the role column
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};