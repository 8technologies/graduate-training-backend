<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class AssignModel extends Model
{
    protected $guarded = ['id'];


    public function student()
    {
        return $this->belongsTo(User::class);
    }
}
