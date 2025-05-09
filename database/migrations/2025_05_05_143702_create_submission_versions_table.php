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
            $table->dropColumn('description');
            $table->dropColumn('documents');
        });

        Schema::create('submission_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->text('description')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->string('file_name', 255);
            $table->integer('file_size')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_versions');
    }
};
