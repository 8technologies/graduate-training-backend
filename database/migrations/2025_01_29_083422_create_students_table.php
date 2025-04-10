<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('program_id');
            $table->foreignId('student_in_take_id');
            $table->foreignId('sponsorship_type_id');
            $table->foreignId('university_id');
            $table->date('DOB')->nullable();
            $table->string('address')->nullable();
            $table->string('student_number')->unique();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('training_status', ['enrolled', 'completed', 'dropped'])->default('enrolled');//(Enum: 'enrolled', 'completed', 'dropped')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
