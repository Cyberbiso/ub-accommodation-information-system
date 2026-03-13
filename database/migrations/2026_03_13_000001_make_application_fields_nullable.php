<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make accommodation_id and preferred_move_in_date nullable
     * so that general (no-room-selected) applications can be saved.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the existing foreign key before changing the column
            $table->dropForeign(['accommodation_id']);

            // Make both columns nullable
            $table->unsignedBigInteger('accommodation_id')->nullable()->change();
            $table->date('preferred_move_in_date')->nullable()->change();

            // Re-add the foreign key allowing NULL (no accommodation yet)
            $table->foreign('accommodation_id')
                  ->references('id')
                  ->on('accommodations')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['accommodation_id']);
            $table->unsignedBigInteger('accommodation_id')->nullable(false)->change();
            $table->date('preferred_move_in_date')->nullable(false)->change();
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->onDelete('cascade');
        });
    }
};
