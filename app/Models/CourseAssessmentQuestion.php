<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseAssessment;
use App\Models\CourseAssessmentQuestionAnswer;
use App\Models\CourseAssessmentQuestionCorrectAnswer;
use App\Models\CourseAssessmentQuestionOption;



class CourseAssessmentQuestion extends Model
{
    use HasFactory;

    public function courseAssessment()
    {
        return $this->belongsTo(CourseAssessment::class);
    }

    public function possibleAnswers()
    {
        return $this->hasMany(CourseAssessmentQuestionAnswer::class);
    }

    public function correctAnswer()
    {
        return $this->hasOne(CourseAssessmentQuestionCorrectAnswer::class);
    }

    public function options()
    {
        return $this->hasOne(CourseAssessmentQuestionOption::class);
    }
}
