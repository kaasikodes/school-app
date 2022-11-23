<?php

namespace App\Http\Resources;

use App\Models\Level;
use App\Models\Currency;


use Illuminate\Http\Resources\Json\JsonResource;

class LevelSchoolFeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ['data'=> parent::toArray($request), 'category'=> $this->category, 'level'=>Level::find($this->level_id), 'currency'=>Currency::find($this->currency_id)];
    }

}
