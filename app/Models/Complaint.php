<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id',
        'supervisor_id',
        'registrar_id',
        'subject',
        'description',
        'response',
        'status',
        'submitted_at',
    ];

    public function student() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function registrar() {
        return $this->belongsTo(User::class, 'registrar_id');
    }

    //complaint messages
    public function messages()
    {
        return $this->hasMany(ComplaintMessage::class, 'complaint_id');
    }

}
