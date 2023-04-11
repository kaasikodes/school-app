<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'description' => $this->description,
        //     'courses'=>$this->courses,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ];
        if($this->author){
          return ['data' => parent::toArray($request), 'courseCount'=> $this->courses->count(), 'author'=>['id'=>$this->admin_id, 'name'=>$this->author->user->name] ];

        }

        return ['data' => parent::toArray($request), 'courseCount'=> $this->courses->count(), 'author'=>null];
    }
}
