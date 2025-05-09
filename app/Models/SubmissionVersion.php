<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionVersion extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'submission_id',
        'file_path',
        'file_name',
        'file_size',
        'is_approved',
        'created_by',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'file_size' => 'integer',
        'created_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(StudentMilestoneSubmission::class, 'submission_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}