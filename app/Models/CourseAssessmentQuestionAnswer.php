<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseAssessmentQuestion;


class CourseAssessmentQuestionAnswer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(CourseAssessmentQuestion::class);
    }
}
