<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class GamesResource extends JsonResource
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
            'id'        => $this->id,
            'title'     => $this->name,
            'slug'      => $this->slug,
            'platform'  => $this->platform,
            'image'     => '/assets/img/game/game-1.jpg',
            'hasApi'    => $this->hasApi,
            'date'  => $this->setApiDate($this->created_at),
        ];
    }

    private function setApiDate($date) {
        $newDate = new Carbon($date);
        $newDateString = $newDate->diffForHumans();
        return $newDateString;
    }
}
