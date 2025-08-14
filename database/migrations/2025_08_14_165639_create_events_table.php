<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->time('event_time');
            $table->string('location');
            $table->enum('category', ['Networking', 'Conference', 'Workshop', 'Social', 'Mentoring']);
            $table->decimal('price', 8, 2)->default(0.00);
            $table->integer('capacity');
            $table->string('image_url')->nullable();
            $table->string('organizer');
            $table->boolean('featured')->default(false);
            $table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming');
            $table->json('tags')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'event_date']);
            $table->index('category');
            $table->index('featured');
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
