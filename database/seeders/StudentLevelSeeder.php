<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'Post Graduate Diploma', 'description' => 'A diploma awarded after completing a bachelor’s degree.'],
            ['name' => 'Masters', 'description' => 'An advanced academic degree beyond a bachelor’s degree.'],
            ['name' => 'PhD', 'description' => 'The highest academic degree, representing expertise in a field of study.'],
        ];

        DB::table('student_levels')->insert($levels);
    }
}
