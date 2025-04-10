<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProgramLevel;
use App\Models\ProgramTrack;
use App\Models\MilestoneProfile;

class Program extends Model
{
    protected $guarded = ['id'];


    public function program_level()
    {
        return $this->belongsTo(ProgramLevel::class);
    }

    public function program_track()
    {
        return $this->belongsTo(ProgramTrack::class);
    }

    public function milestone_profile()
    {
        return $this->belongsTo(MilestoneProfile::class);
    }

}
