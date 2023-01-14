<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Admin;


class Department extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
