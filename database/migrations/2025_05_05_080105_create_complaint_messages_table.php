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
        Schema::table('complaints', function (Blueprint $table) {
            $table->text('documents')->nullable();
        });
        
        Schema::create('complaint_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('complaint_id');
            $table->foreignId('sender_id')->constrained('users');
            $table->text('message');
            $table->text('documents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_messages');
    }
};
