<?php

namespace App\Http\Resources;

use App\Models\Level;
use App\Models\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseParticipantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $student = Student::find($this->student_id);

        return ['data' => parent::toArray($request), 'user'=>$student ? $student->user : null, ];

    }
}
