<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CourseParticipant;
use App\Models\Custodian;



class Student extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function courseParticipants()
    {
        return $this->hasMany(CourseParticipant::class);
    }

    public function custodians()
    {
        return $this->belongsToMany(Custodian::class)->withPivot('id');
    }
}
