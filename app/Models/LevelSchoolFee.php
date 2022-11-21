<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;


class LevelSchoolFee extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'level_school_fees';

    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
