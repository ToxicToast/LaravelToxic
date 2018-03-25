<?php

namespace App\Http\Resources\Overwatch;

use Illuminate\Http\Resources\Json\JsonResource;

class TrendsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'player_rank'	=> $this->player_rank,
            'player_tier'	=> $this->player_tier
        ];
    }
}
