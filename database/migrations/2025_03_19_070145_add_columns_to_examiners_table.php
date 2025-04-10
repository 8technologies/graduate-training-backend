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
        Schema::table('examiners', function (Blueprint $table) {
            
            $table->timestamps();
        });
        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropUnique('years_of_experience');
            $table->dropUnique(['department']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examiners', function (Blueprint $table) {
            //
        });
    }
};
