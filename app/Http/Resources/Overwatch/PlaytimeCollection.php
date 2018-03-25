<?php

namespace App\Http\Resources\Overwatch;

use Illuminate\Http\Resources\Json\ResourceCollection;
use \App\Http\Resources\Overwatch\PlaytimeResource;

class PlaytimeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return PlaytimeResource::collection($this->collection);
    }
}
