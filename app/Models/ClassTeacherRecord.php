<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Staff;
use App\Models\Level;
use App\Models\CourseTeacher;



class ClassTeacherRecord extends Model
{
    use HasFactory;
    protected $table = 'class_teacher_records';
    protected $guarded = [];

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    



}
