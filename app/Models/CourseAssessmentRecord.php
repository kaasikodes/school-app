<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseAssessment;


class CourseAssessmentRecord extends Model
{
    use HasFactory;

    public function courseAssessment()
    {
        return $this->belongsTo(CourseAssessment::class);
    }
    
}
