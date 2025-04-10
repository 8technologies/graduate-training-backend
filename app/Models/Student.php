<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// use Illuminate\Database\Eloquent\SoftDeletes;
class Student extends Model
{
    use HasFactory;


    protected $guarded = ['id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sponsorshipType()
    {
        return $this->belongsTo(SponsorshipType::class);
    }

    public function studentIntake()
    {
        return $this->belongsTo(StudentInTake::class, 'student_in_take_id');
    }

    public function supervisor()
{
    return $this->belongsToMany(Supervisor::class, 'assign_models', 'supervisor_id', 'student_id');
}


    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Generate a unique student number in the format ST-XXXXXXXX.
     * (XXXXXXXX = 8 random digits)
     *
     * @return string
     */
    public static function generateStudentNumber()
    {
        do {
            // Generate 8 random digits.
            $randomDigits = '';
            for ($i = 0; $i < 8; $i++) {
                $randomDigits .= mt_rand(0, 9);
            }

            // Prefix with 'ST-'.
            $studentNumber = 'ST-' . $randomDigits;

        } while (self::where('student_number', $studentNumber)->exists());

        return $studentNumber;
    }

    /**
     * Automatically assign a student number on create if none is provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->student_number)) {
                $model->student_number = self::generateStudentNumber();
            }
        });
    }

}
