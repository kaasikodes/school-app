<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;
use App\Models\Level;

class Requisition extends Model
{
    use HasFactory;

    protected $table = 'requisitions';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    public function currentApprover()
    {
        return $this->belongsTo(User::class, 'current_approver_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function level()
    {
        return $this->Level(Course::class);
    }
}
