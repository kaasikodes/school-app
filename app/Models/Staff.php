<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\LevelTeacher;
use App\Models\CourseTeacher;


class Staff extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseTeachers()
    {
        return $this->hasMany(CourseTeacher::class);
    }

    public function levelTeachers()
    {
        return $this->hasMany(LevelTeacher::class);
    }
}
