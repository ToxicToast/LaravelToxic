<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\FetchOverwatchProfiles;
use App\Models\Overwatch\Player;

class CallOverwatchProfiles extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overwatch:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the Overwatch Players';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = Player::OnlyActive()
        ->orderBy('id', 'ASC')
        ->get();
        if (!$model->isEmpty()) {
            foreach($model as $player) {
                $user = $player->name;
                $tag = $player->hashtag;
                $userId = $player->id;
                //
                $data = $this->dispatch((new FetchOverwatchProfiles([
                    'user'      => $user,
                    'tag'       => $tag,
                    'userId'    => $userId
                ]))->onQueue('overwatch_profiles'));
                sleep(15);
                $this->info('Fetching Data for ' . $user . ' finished.');
            }
        }
    }
}
