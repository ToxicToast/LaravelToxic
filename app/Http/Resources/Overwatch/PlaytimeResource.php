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
            'character'		=> $this->character_name,
            'time'		    => $this->character_time,
            'role'          => $this->setCharacterRoles($this->character_name)
        ];
    }

    private function setCharacterRoles($character) {
        $array = [];
        //
        $array['moira'] = 'Support';
        $array['mercy'] = 'Support';
        $array['zenyatta'] = 'Support';
        $array['ana'] = 'Support';
        $array['lucio'] = 'Support';
        $array['symmetra'] = 'Support';
        //
        $array['dva'] = 'Tank';
        $array['reinhardt'] = 'Tank';
        $array['roadhog'] = 'Tank';
        $array['winston'] = 'Tank';
        $array['zarya'] = 'Tank';
        $array['orisa'] = 'Tank';
        //
        $array['genji'] = 'Offense';
        $array['soldier76'] = 'Offense';
        $array['tracer'] = 'Offense';
        $array['mccree'] = 'Offense';
        $array['pharah'] = 'Offense';
        $array['reaper'] = 'Offense';
        $array['sombra'] = 'Offense';
        $array['doomfist'] = 'Offense';
        //
        $array['junkrat'] = 'Defense';
        $array['widowmaker'] = 'Defense';
        $array['hanzo'] = 'Defense';
        $array['mei'] = 'Defense';
        $array['torbjorn'] = 'Defense';
        $array['bastion'] = 'Defense';
        //
        return $array[$character];
    }
}
