<?php

namespace App\Http\Resources\Overwatch;

use Illuminate\Http\Resources\Json\ResourceCollection;
use \App\Http\Resources\Overwatch\RankedResource;

class RankedCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return RankedResource::collection($this->collection);
    }
}
