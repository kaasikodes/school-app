<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\SchoolSession;




class SchoolSessionSetting extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'school_session_setting';

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
     public function getSessionIdAttribute($value)
     {
         return SchoolSession::find($value);
     }

    public function school()
    {
        return $this->belongsTo(School::class);
    }





}
