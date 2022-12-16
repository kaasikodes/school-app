<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Level;
use App\Models\CourseTeacher;



class ClassTeacherRecord extends Model
{
    use HasFactory;
    protected $table = 'class_teacher_records';
    protected $guarded = [];

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    



}
