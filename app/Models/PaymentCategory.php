<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\LevelSchoolFee;


class PaymentCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'school_fee_categories';



    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function levelFees()
    {
        return $this->hasMany(LevelSchoolFee::class, 'fee_category_id');
    }

}
