<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Level;




class CourseTeacherRecord extends Model
{
    use HasFactory;
    protected $table = 'course_overview_records';
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }



}
