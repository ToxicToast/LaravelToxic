<?php

namespace App\Http\Resources\Overwatch;

use Illuminate\Http\Resources\Json\ResourceCollection;
use \App\Http\Resources\Overwatch\PlayerResource;

class PlayerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return PlayerResource::collection($this->collection);
    }
}
