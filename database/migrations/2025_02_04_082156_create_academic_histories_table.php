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
        Schema::create('academic_histories', function (Blueprint $table) {
            $table->id();
            $table->string('degree'); // Degree earned (e.g., B.A., M.S., Ph.D.)
            $table->string('institution');
            $table->string('field_of_study');
            $table->foreignId('user_id')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('thesis_title')->nullable();
            $table->string('honors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_histories');
    }
};
