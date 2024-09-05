<?php

namespace App\Console\Commands;

use App\Models\BettingRecord;
use Illuminate\Console\Command;
use App\Models\FootballMaungGroup;
use App\Services\Ballone\MaungServiceTest;
use App\Services\Ballone\MaungServiceCheck;

class MaungCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:maungs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Betting Record Table - results & amount column null to default value';

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
     * @return int
     */
    public function handle()
    {

        (new MaungServiceTest())->execute();

        echo "done";
    }
}
