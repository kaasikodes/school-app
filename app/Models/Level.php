<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LevelTeacher;
use App\Models\ClassTeacherRecord;
use App\Models\Course;
use App\Models\User;


class Level extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    

    public function schoolSessions()
    {
        return $this->belongsToMany(SchoolSession::class, 'level_school_session', 'level_id', 'school_session_id')->withPivot('id');
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('id');
    }
    public function teachers()
    {
        return $this->hasMany(LevelTeacher::class);
    }
    public function classSessionTeachers()
    {
        return $this->hasMany(ClassTeacherRecord::class, 'level_id');
    }
    
}
