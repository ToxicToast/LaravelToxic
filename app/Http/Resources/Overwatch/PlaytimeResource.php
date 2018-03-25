<?php

namespace App\Http\Resources\Overwatch;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaytimeResource extends JsonResource
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
            'id'            => $this->player_id,
						'character'			=> $this->character_name,
						'time'					=> $this->character_time
        ];
    }
}
