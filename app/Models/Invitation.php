<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;
use App\Models\SchoolSession;





class Invitation extends Model
{
    use HasFactory;
    use Notifiable;
    protected $table = 'invitations';
    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function schoolSession()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id');
    }



}
