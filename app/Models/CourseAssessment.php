<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\CourseAssessmentComplaint;
use App\Models\CourseAssessmentRecord;
use App\Models\CourseAssessmentSection;


class CourseAssessment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function createdBy()
    {
        return $this->belongsTo(CourseTeacher::class);
    }

    public function complaints()
    {
        return $this->hasMany(CourseAssessmentComplaint::class);
    }

    public function records()
    {
        return $this->hasMany(CourseAssessmentRecord::class);
    }

    public function sections()
    {
        return $this->hasMany(CourseAssessmentSection::class);
    }
}
