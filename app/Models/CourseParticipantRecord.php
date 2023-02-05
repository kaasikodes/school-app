<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Level;
use App\Models\CourseParticipant;
use App\Models\Student;




class CourseParticipantRecord extends Model
{
    use HasFactory;
    protected $table = 'course_participant_records';
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }


}
