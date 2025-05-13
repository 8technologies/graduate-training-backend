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
        Schema::table('student_milestone_submissions', function (Blueprint $table) {
            $table->float('grade')->nullable(); // Grade out of 100, for example
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_student_milestone_submissionssubmission', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
    }
};
