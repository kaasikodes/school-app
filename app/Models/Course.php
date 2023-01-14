<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\CourseLesson;
use App\Models\CourseAssessment;
use App\Models\CourseTeacher;
use App\Models\CourseTeacherRecord;

use App\Models\CourseParticipant;
use App\Models\Level;


class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function levels()
    {
        return $this->belongsToMany(Level::class)->withPivot('id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class);
    }

    public function assessments()
    {
        return $this->hasMany(CourseAssessment::class);
    }

    public function teachers()
    {
        return $this->hasMany(CourseTeacher::class);
    }
    public function courseTeacherRecords()
    {
        return $this->hasMany(CourseTeacherRecord::class);
    }

    public function participants()
    {
        return $this->hasMany(CourseParticipant::class);
    }

}
