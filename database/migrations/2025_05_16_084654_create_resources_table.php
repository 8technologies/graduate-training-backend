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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->nullable(); // book, journal, etc.
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('year')->nullable();
            $table->foreignId('program_id');
            $table->string('format')->nullable();
            $table->string('url')->nullable(); // for online materials
            $table->string('file_path')->nullable(); // for uploaded PDFs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
