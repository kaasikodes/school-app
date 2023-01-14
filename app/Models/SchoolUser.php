<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Level;
use App\Models\CourseTeacher;



class SchoolUser extends Model
{
    use HasFactory;
    protected $table = 'school_user';
    protected $guarded = [];

  


}
