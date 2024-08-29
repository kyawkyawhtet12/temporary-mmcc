<?php

namespace App\Console\Commands;

use App\Actions\WinRecordAction;
use Illuminate\Console\Command;

class WinRecordAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-win-record {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $type = $this->argument('type');

        $round = $this->ask('What is round number ?');

        if($type == 'Maung')
        {
           (new WinRecordAction())->executeCreate('Maung', $round);
        }
    }
}
