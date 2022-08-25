<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseAssessmentQuestion;


class CourseAssessmentQuestionOption extends Model
{
    use HasFactory;

    public function question()
    {
        return $this->belongsTo(CourseAssessmentQuestion::class);
    }
}
