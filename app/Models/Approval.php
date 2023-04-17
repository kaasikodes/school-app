<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Requisition;
use App\Models\Level;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'approvals';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
  
}
