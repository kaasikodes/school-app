<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\SchoolSessionSetting;
use App\Models\SchoolSession;





class SchoolCourseRecordTemplate extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'school_course_record_templates';




    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function sessionsUsedIn()
    {
        return $this->hasMany(SchoolSessionSetting::class, 'course_record_template_id');
    }



}
