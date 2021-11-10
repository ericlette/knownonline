<?php

namespace App\Console\Commands;

use App\Services\MainService;
use Illuminate\Console\Command;

class scheduleOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:api-vtex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from vtex api and save to database';

    /**
     *
     * @var MainService
     */
    protected $mainService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MainService $mainService)
    {

        parent::__construct();
        $this->mainService = $mainService;

    }

    /**
     * Execute the console command.
     *
     * @return String
     */
    public function handle()
    {
        $this->mainService->handle()
            ? $this->info('The command was successful!')
            : $this->info('The command was not executed correctly check the log');

    }
}
