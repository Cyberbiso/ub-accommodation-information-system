<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'surname')) {
                $table->string('surname')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('surname');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['student_id', 'surname', 'first_name'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};