<?php

namespace App\Http\Resources\Overwatch;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Overwatch\TrendsResource;
use App\Http\Resources\Overwatch\TrendsCollection;
use App\Http\Resources\Overwatch\PlayerResource;
use App\Http\Resources\Overwatch\PlaytimeCollection;

class RankedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $characters = new PlaytimeCollection($this->playtime);
        return [
            'id'            => $this->player_id,
            'rank'          => $this->player_rank,
            'level'         => $this->calculateLevel($this->player_prestige, $this->player_level),
            'avatar'        => $this->player_avatar,
            'tier'          => $this->player_tier,
            'total'         => $this->total,
            'wins'          => $this->wins,
            'loses'         => $this->loses,
            'ties'          => $this->ties,
            'role'          => $this->getPlayerRole($characters),
            'nexttier'      => $this->calculateNextTier($this->player_rank),
            'player'        => new PlayerResource($this->player),
            'trends'        => new TrendsCollection($this->trends),
            'playtime'      => $characters
        ];
    }

    private function calculateLevel($prestige, $level) {
        $endLevel = $prestige * 100;
        return $endLevel + $level;
    }

    private function calculateNextTier($points) {
        $array = [
            'league'    => 'bronze',
            'needed'    => 1,
        ];
        if ($points >= 1 && $points < 1500) {
            $array = [
                'league'    => 'silver',
                'needed'    => 1500 - $points,
            ];
        } elseif ($points >= 1500 && $points < 2000) {
            $array = [
                'league'    => 'gold',
                'needed'    => 2000 - $points,
            ];
        } elseif ($points >= 2000 && $points < 2500) {
            $array = [
                'league'    => 'platinum',
                'needed'    => 2500 - $points,
            ];
        } elseif ($points >= 2500 && $points < 3000) {
            $array = [
                'league'    => 'diamond',
                'needed'    => 3000 - $points,
            ];
        } elseif ($points >= 3000 && $points < 3500) {
            $array = [
                'league'    => 'master',
                'needed'    => 3500 - $points,
            ];
        } elseif ($points >= 3500 && $points < 4000) {
            $array = [
                'league'    => 'grandmaster',
                'needed'    => 4000 - $points,
            ];
        }
        return $array;
    }

    private function getPlayerRole($characters) {
        $roles = [];
        foreach($characters as $character) {
            $roles[] = $this->setCharacterRoles($character->character_name);
        }
        $playerRoles = array_unique($roles);
        if (count($playerRoles) === 1) {
            return $playerRoles[0];
        } elseif (count($playerRoles) === 3) {
            return 'Flex';
        } else {
            return 'Flex - ' . $playerRoles[0];
        }
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