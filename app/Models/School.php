<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class School extends Model
{
    use HasFactory;
    protected $guarded = [];

    // A user is part of many schools and can pay different roles in that school 
    // Likewise a school has many users

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    // A user is part of many schools and can pay different roles in that school

    public function sessions()
    {
        return $this->hasMany(SchoolSession::class);
    }



}
