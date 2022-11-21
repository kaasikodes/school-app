<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\CourseParticipant;



class CourseParticipantRecord extends Model
{
    use HasFactory;
    protected $table = 'course_participant_records';
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function participant()
    {
        return $this->belongsTo(CourseParticipant::class);
    }


}
