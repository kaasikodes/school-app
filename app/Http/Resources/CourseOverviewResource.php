<?php

namespace App\Http\Resources;

use App\Models\Level;
use App\Models\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseOverviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      

        return ['data' => parent::toArray($request) ];

    }
}
