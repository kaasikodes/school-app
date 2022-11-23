<?php

namespace App\Http\Resources;

use App\Models\Level;
use App\Models\EnrolledStudent;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $sessionId = $request->sessionId;
        if ($sessionId) {
            $enrollmentStudent = EnrolledStudent::where('student_id',$this->id)->where('school_session_id', $sessionId)->first();
            $enrollmentStatus = $enrollmentStudent ? true : false;

            return ['data' => parent::toArray($request), 'user'=> $this->user, 'currentLevel'=>Level::find($this->current_level_id), 'currentSessionEnrollmentStatus'=>$enrollmentStatus];
        }
        

        return ['data' => parent::toArray($request), 'user'=> $this->user, 'currentLevel'=>Level::find($this->current_level_id)];

    }
}
