<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use App\Models\University;
use App\Models\Program;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Role;

class User extends Authenticatable
{

    use HasApiTokens, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'telephone',
        'password',
        'university_id',
        'role_id',
    ];


    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'course_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }
    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
    }
    public function examiner()
    {
        return $this->hasOne(Examiner::class);
    }

    /**
     * Encrypt the user password before saving
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password, [
            'rounds' => 12,
        ]);
    }


    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }


}
