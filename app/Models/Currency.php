<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;




class Currency extends Model
{
    use HasFactory;

    use HasFactory;

    protected $guarded = [];
    protected $table = 'currencies';



    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
