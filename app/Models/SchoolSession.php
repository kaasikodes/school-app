<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSession extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'level_school_session','school_session_id', 'level_id')->withPivot('id');
    }

}
