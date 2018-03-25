<?php

namespace App\Http\Resources\Overwatch;

use Illuminate\Http\Resources\Json\ResourceCollection;
use \App\Http\Resources\Overwatch\TrendsResource;

class TrendsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return TrendsResource::collection($this->collection);
    }
}
