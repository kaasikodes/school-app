<?php

namespace App\Http\Resources;

use App\Models\LevelSchoolFee;
use App\Models\PaymentCategory;
use App\Models\User;
use App\Models\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentSessionPaymentResource extends JsonResource
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
        return ['data' => parent::toArray($request), 'user'=> User::find($student->user_id), 'levelFee'=>LevelSchoolFee::find($this->level_fee_id), 'recorder'=> User::find($this->recorder_id)];

    }
}
