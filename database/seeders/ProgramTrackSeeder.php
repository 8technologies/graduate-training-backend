<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramTrack;

class ProgramTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program_tracks = array(
            array('name' => 'research only'),
            array('name' => 'class work and research'),
        );

        ProgramTrack::truncate();
        foreach ($program_tracks as $program_track) {
            ProgramTrack::create($program_track);
        }
    }
}
