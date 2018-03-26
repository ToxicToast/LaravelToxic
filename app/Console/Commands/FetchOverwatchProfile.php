<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\FetchOverwatchProfiles;

class FetchOverwatchProfile extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overwatch:profiles {user} {tag} {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the Overwatch Player';

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
        $user = $this->argument('user');
        $tag = $this->argument('tag');
        $userId = $this->argument('userId');
        //
        $this->dispatch((new FetchOverwatchProfiles([
            'user'      => $user,
            'tag'       => $tag,
            'userId'    => $userId
        ]))->onQueue('overwatch_profiles'));
    }
}
