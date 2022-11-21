<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PaymentCategory;
use App\Models\LevelSchoolFee;


class School extends Model
{
    use HasFactory;
    protected $guarded = [];

    // A user is part of many schools and can pay different roles in that school
    // Likewise a school has many users

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['choosen_role','school_user_roles']);
    }
    // A user is part of many schools and can pay different roles in that school

    public function sessions()
    {
        return $this->hasMany(SchoolSession::class);
    }
    public function paymentCategories()
    {
        return $this->hasMany(PaymentCategory::class);
    }
    public function levelSchoolFees()
    {
        return $this->hasMany(LevelSchoolFee::class);
    }



}
