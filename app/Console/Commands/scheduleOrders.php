<?php

namespace App\Console\Commands;

use App\Services\VtexApiService;
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
    protected $description = 'Get data from API VTEX';

    /**
     *
     * @var VtexApiService
     */
    protected $vtexService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VtexApiService $vtexService)
    {
        parent::__construct();
        $this->vtexService = $vtexService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo $this->vtexService->getData();
        //return Command::SUCCESS;
    }
}
