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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('milestone_name');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->foreignId('milestone_profile_id')->nullable();
            $table->foreignId('duration_unit_id')
                ->nullable();
            $table->foreignId('university_id')
                ->nullable();
            $table->boolean('requires_submission')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
