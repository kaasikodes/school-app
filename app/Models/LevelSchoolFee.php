<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\PaymentCatgeory;


class LevelSchoolFee extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'level_school_fees';

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function category()
    {
        return $this->belongsTo(PaymentCategory::class, 'fee_category_id');
    }

}
