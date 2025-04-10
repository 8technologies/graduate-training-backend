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
        Schema::table('supervisors', function (Blueprint $table) {

            $table->string('supervisor_code')->unique();
            $table->string('designation')->nullable();
            $table->string('department')->unique();
            $table->string('years_of_experience')->unique();
            $table->enum('gender', ['male', 'female'])->nullable();
            // $table->string('department')->unique();
            
        });
        Schema::create('examiners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('examiner_code')->unique();
            $table->string('expertise');
            $table->string('academic_credentials');
            $table->string('job_title')->nullable();
            $table->integer('years_of_experience')->nullable();

            // $table->string('department')->unique();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn('supervisor_code');
            $table->dropColumn('designation');
            $table->dropColumn('department');
            $table->dropColumn('years_of_experience');
            $table->dropColumn('gender', ['male', 'female']);
            
        });

        Schema::dropIfExists('examiners');
    }
};
