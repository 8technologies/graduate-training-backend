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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('university_id');
            $table->string('program_code')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('program_level_id');
            $table->foreignId('program_track_id');
            $table->integer('duration')->nullable();
            $table->foreignId('duration_unit_id')->nullable();
            $table->foreignId('milestone_profile_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
