<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\CourseTeacher;



class CourseTeacherRecord extends Model
{
    use HasFactory;
    protected $table = 'course_teacher_records';
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function teacher()
    {
        return $this->belongsTo(CourseTeacher::class);
    }


}
