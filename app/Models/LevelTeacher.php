<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Level;
use App\Models\Staff;



class LevelTeacher extends Model
{
    use HasFactory;

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
