<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BannedUsers;
use Carbon\Carbon;

class Unblock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:unblock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unblock banned users when a specific time is reached';

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
        BannedUsers::where('forever_ban', '!=', '1')->where('temporary_ban', '<=', Carbon::now())->delete();

        
    }
}
