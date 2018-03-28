<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Overwatch\Player;
use App\Models\Overwatch\Competitive;
use App\Models\Overwatch\Trends;
use App\Models\Overwatch\Playtime;

use GuzzleHttp\Client;

class FetchOverwatchProfiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $profile = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($profile)
    {
        $this->profile = $profile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->getApiData();
        $this->savePlayerOldRanking();
        $this->savePlayerRanking($data['stats']['competitive']['overall_stats'], $data['stats']['competitive']['game_stats']);
        $this->deletePlayerCharacterPlaytime();
        $this->savePlayerCharacterPlaytime($data['heroes']['playtime']['competitive']);
    }

    private function getApiData() {
        $bnetAccount = $this->profile['user'] . "-" .  $this->profile['tag'];
        $url = "https://owapi.net/api/v3/u/" . urlencode($bnetAccount) . "/blob?platform=pc&region=eu";
        //
        $client = new Client();
        $response = $client->get($url);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json['eu'];
    }

    private function getMostPlayedCharacters($characters) {
        $playtimeArray = [];
        foreach($characters as $character => $playtime) {
            $playtimeArray[] = [
                'character' => $character,
                'playtime'  => $playtime
            ];
        }
        $playtimeCollection = collect($playtimeArray)
        ->sortByDesc('playtime')
        ->slice(0, 3);
        return $playtimeCollection;
    }

    private function getTodaysRanking($rankedData) {
       $userId = $this->profile['userId'];
    }

    private function savePlayerRanking($rankedData, $gameStats) {
        $statsArray = [
            'player_id'             => $this->profile['userId'],
            'player_rank'           => $rankedData['comprank'],
            'player_level'          => $rankedData['level'],
            'player_prestige'       => $rankedData['prestige'],
            'player_tier'           => $rankedData['tier'],
            'player_avatar'         => $rankedData['avatar'],
            'total'                 => isset($gameStats['games_played']),
            'wins'                  => isset($gameStats['games_won']) ? intval($gameStats['games_won']) : 0,
            'loses'                 => isset($gameStats['games_lost']) ? intval($gameStats['games_lost']) : $rankedData['losses'],
            'ties'                  => isset($gameStats['games_tied']) ? intval($gameStats['games_tied']) : 0,
            'player_gold_medals'    => isset($gameStats['medals_gold']) ? intval($gameStats['medals_gold']) : 0,
            'player_silver_medals'  => isset($gameStats['medals_silver']) ? intval($gameStats['medals_silver']) : 0,
            'player_bronze_medals'  => isset($gameStats['medals_bronze']) ? intval($gameStats['medals_bronze']) : 0,
        ];
        //
        Competitive::updateOrCreate([
            'player_id' => $this->profile['userId'],
        ], $statsArray);
    }

    private function savePlayerOldRanking() {
        $model = Competitive::where('player_id', $this->profile['userId'])->first();
        if ($model) {
            $payload = [
                'player_id'     => $this->profile['userId'],
                'player_rank'   => $model->player_rank,
                'player_tier'   => $model->player_tier
            ];
            $trends = new Trends($payload);
            $trends->save();
        }
    }

    private function savePlayerCharacterPlaytime($characters) {
        $array = [];
        $data = $this->getMostPlayedCharacters($characters);
        foreach($data as $item) {
            $array = [
                'player_id'         => $this->profile['userId'],
                'character_name'    => $item['character'],
                'character_time'    => $item['playtime']
            ];
            $playtime = new Playtime($array);
            $playtime->save();
        }
    }

    private function deletePlayerCharacterPlaytime() {
        $model = Playtime::where('player_id', $this->profile['userId']);
        $model->delete();
    }
}
