<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Level;
use App\Models\Staff;
use App\Models\CourseTeacher;



class CourseTeacherRecord extends Model
{
    use HasFactory;
    protected $table = 'course_teacher_records';
    protected $guarded = [];
    protected $with = ['level','course','staff.user'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function teacher()
    {
        return $this->belongsTo(CourseTeacher::class);
    }


}
