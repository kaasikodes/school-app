<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CourseAssessment;


class CourseAssessmentSection extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function courseAssessment()
    {
        return $this->belongsTo(CourseAssessment::class);
    }
}
