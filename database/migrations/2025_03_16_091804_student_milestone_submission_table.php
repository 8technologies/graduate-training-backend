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
        Schema::create('student_milestone_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained(table: 'students')->onDelete('cascade');
            $table->foreignId('milestone_id');
            $table->text('description')->nullable();
            $table->string('documents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_milestone_submissions');
    }
};
