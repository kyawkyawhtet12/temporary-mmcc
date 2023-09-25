<?php

namespace App\Console\Commands;

use App\Models\BettingRecord;
use Illuminate\Console\Command;

class CheckRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:records';

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
        BettingRecord::whereNull("result")
                    ->orWhereNull('win_amount')
                    ->update([
                        'result' => 'No Prize',
                        'win_amount' => 0
                    ]);

        echo "done";
    }
}
