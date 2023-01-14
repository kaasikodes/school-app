<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->author){
          return ['data' => parent::toArray($request), 'courseCount'=> $this->courses->count(), 'author'=>['id'=>$this->admin_id, 'name'=>$this->author->user->name] ];

        }

        return ['data' => parent::toArray($request), 'courseCount'=> $this->courses->count(), 'author'=>null ];
    }
}
