<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class RolesResource extends Resource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'date'	=> $this->setApiDate($this->created_at)
        ];
        // return parent::toArray($request);
    }

    private function setApiDate($date) {
        $newDate = new Carbon($date);
        $newDateString = $newDate->diffForHumans();
        return $newDateString;
    }
}
