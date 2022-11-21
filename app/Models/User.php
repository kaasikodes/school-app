<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Custodian;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Admin;
use App\Models\School;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    // A user is part of many schools and can pay different roles in that school
    // Likewise a school has many users

    public function schools()
    {
        return $this->belongsToMany(School::class)->withPivot(['choosen_role','school_user_roles']);
    }
    // A user is part of many schools and can pay different roles in that school
    public function admin()
    {
        return $this->hasMany(Admin::class);
    }

    public function custodian()
    {
        return $this->hasMany(Custodian::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
