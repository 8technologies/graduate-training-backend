<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DurationUnit;

class DurationUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $duration_units = array(
            array('unit' => 'Day(s)'),
            array('unit' => 'Week(s)'),
            array('unit' => 'Month(s)'),
            array('unit' => 'Year(s)'),
        );

        DurationUnit::truncate();
        foreach ($duration_units as $duration_unit) {
            DurationUnit::create($duration_unit);
        }
    }
}
