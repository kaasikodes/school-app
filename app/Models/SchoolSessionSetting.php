<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;


class SchoolSessionSetting extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'school_session_setting';



    public function school()
    {
        return $this->belongsTo(School::class);
    }



}
