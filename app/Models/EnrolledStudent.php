<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;




class EnrolledStudent extends Model
{
    use HasFactory;

    

    protected $guarded = [];
    protected $table = 'enrolled_students';



    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
