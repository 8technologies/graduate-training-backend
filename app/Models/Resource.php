<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'title',
        'type',
        'description',
        'author',
        'year',
        'program_id',
        'format',
        'url',
        'file_path',
    ];

     public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}

