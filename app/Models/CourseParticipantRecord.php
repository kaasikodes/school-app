<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Level;
use App\Models\CourseParticipant;
use App\Models\CourseParticipantRecord;
use App\Models\Student;




class CourseParticipantRecord extends Model
{
    use HasFactory;
    protected $table = 'course_participant_records';
    protected $guarded = [];
    protected $appends = ['session_level_course_stats'];


    public function getSessionLevelCourseStatsAttribute(){

        $participants = CourseParticipantRecord::where('school_session_id',$this->school_session_id)->where('course_id',$this->course_id)->where('level_id',$this->level_id) 
        ->get();
        $totalStudentsOfferingCourseThisSession = $participants->count();
        $highestScore = $participants->max('total');
        $lowestScore = $participants->min('total');
        $classAverage = $participants->avg('total');
        $participantsP = $participants->pluck('total')->sortByDesc('total')->unique();
        $participantsP = $participantsP->values()->all();
   
      
        $position = 0;
        foreach ($participantsP as $key => $value) {
            if($value === $this->total){
                $position = $key + 1;
                break;
            }
        }
        return [
            'totalStudents' => $totalStudentsOfferingCourseThisSession,
            'highestScore'=>$highestScore,
            'lowestScore'=>$lowestScore,
            'classAverage'=>$classAverage,
            'position'=>$position,
            

        ];
    }

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
