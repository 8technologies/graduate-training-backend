<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Meeting.php
class Meeting extends Model
{
    protected $fillable = [
        'student_id',
        'supervisor_id',
        'title',
        'description',
        'scheduled_at',
        'mode',
        'location',
        'status'
    ];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}

