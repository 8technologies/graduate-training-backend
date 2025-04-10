<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\University;
use App\Models\Milestone;
class MilestoneProfile extends Model
{
    protected $guarded = ['id'];


    public function university()
    {
        return $this->belongsTo(University::class);
    }


    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }
}
