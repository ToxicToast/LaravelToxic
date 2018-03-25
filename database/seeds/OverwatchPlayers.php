<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OverwatchPlayers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array[] = $this->getPlayerArray('ToxicToast', 1192);
        $array[] = $this->getPlayerArray('BeLoor', 2339);
        $array[] = $this->getPlayerArray('DragonMG', 2607);
        $array[] = $this->getPlayerArray('HanterGER', 2134);
        $array[] = $this->getPlayerArray('Noobster', 21680);
        $array[] = $this->getPlayerArray('Sensimillia', 21307);
        DB::table('overwatch_players')->insert($array);
    }

    private function getPlayerArray($name, $hashtag) {
        return [
            'name'          => $name,
            'slug'          => str_slug($name),
            'hashtag'       => $hashtag,
            'active'        => '1',
            'created_at'    => Carbon::now()
        ];
    }
}
