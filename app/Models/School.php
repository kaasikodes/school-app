<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PaymentCategory;
use App\Models\LevelSchoolFee;
use App\Models\Department;
use App\Models\Level;
use App\Models\Course;
use App\Models\Staff;
use App\Models\Student;


class School extends Model
{
    use HasFactory;
    protected $guarded = [];

    // A user is part of many schools and can pay different roles in that school
    // Likewise a school has many users

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['choosen_role','school_user_roles', 'staff_id', 'custodian_id', 'admin_id','student_id']);
    }
    // A user is part of many schools and can pay different roles in that school

    public function sessions()
    {
        return $this->hasMany(SchoolSession::class);
    }
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
    public function levels()
    {
        return $this->hasMany(Level::class);
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function paymentCategories()
    {
        return $this->hasMany(PaymentCategory::class);
    }
    public function levelSchoolFees()
    {
        return $this->hasMany(LevelSchoolFee::class);
    }



}
