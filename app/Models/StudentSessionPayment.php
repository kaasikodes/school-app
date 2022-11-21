<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;


class StudentSessionPayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'student_session_payments';



    public function student()
    {
        return $this->belongsTo(Student::class);
    }



}
