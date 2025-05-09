<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentMilestoneSubmission extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'milestone_id',
        'student_id',
        'status',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(SubmissionVersion::class, 'submission_id')->orderBy('created_at', 'desc');
    }

    public function latestVersion()
    {
        return $this->versions()->latest()->first();
    }

    public function approvedVersion()
    {
        return $this->versions()->where('is_approved', true)->first();
    }
}
