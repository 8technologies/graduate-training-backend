<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExaminerStudent extends Model
{
    protected $table = 'examiner_student';
    protected $fillable = ['examiner_id', 'student_id'];

    public function examiner()
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
