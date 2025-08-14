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
    Schema::table('students', function (Blueprint $table) {
        $table->year('graduation_year')->nullable();
        $table->text('about')->nullable();
        $table->string('company')->nullable();
        $table->string('position')->nullable();
    });
}

public function down()
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn([
            'graduation_year',
            'about',
            'company',
            'position'
        ]);
    });
}

};
