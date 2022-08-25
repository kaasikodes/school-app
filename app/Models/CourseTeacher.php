<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseAssessment;
use App\Models\Course;
use App\Models\Staff;


class CourseTeacher extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    

    public function courseAssessments()
    {
        return $this->hasMany(CourseAssessment::class);
    }
}
