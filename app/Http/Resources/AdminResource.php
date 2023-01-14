<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            // are you going to enroll staff
        }
        return ['data' => parent::toArray($request), 'user'=> $this->user];
    }
}
