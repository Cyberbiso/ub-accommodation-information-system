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
        Schema::table('student_documents', function (Blueprint $table) {
            if (Schema::hasColumn('student_documents', 'file_path') && !Schema::hasColumn('student_documents', 'path')) {
                $table->renameColumn('file_path', 'path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            if (Schema::hasColumn('student_documents', 'path') && !Schema::hasColumn('student_documents', 'file_path')) {
                $table->renameColumn('path', 'file_path');
            }
        });
    }
};
