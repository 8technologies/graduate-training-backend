<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintMessage extends Model
{
    protected $fillable = [
        'complaint_id',
        'sender_id',
        'message',
        'documents',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}
