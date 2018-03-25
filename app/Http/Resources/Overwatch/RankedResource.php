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
            'player'        => new PlayerResource($this->player),
            'trends'        => new TrendsCollection($this->trends),
            'playtime'      => new PlaytimeCollection($this->playtime)
        ];
    }

    private function calculateLevel($prestige, $level) {
        $endLevel = $prestige * 100;
        return $endLevel + $level;
    }
}
