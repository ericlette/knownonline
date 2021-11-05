<?php

namespace App\Console\Commands;

use App\Repositories\OrderDetailRepo;
use App\Repositories\OrderPaymentRepo;
use App\Repositories\OrderRepo;
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
    protected $description = 'Get data from vtex api and save to database';

    /**
     *
     * @var VtexApiService
     */
    protected $vtexService;

    /**
     *
     * @var OrderRepo
     */
    protected $orderRepo;

    /**
     *
     * @var OrderDetailRepo
     */
    protected $orderDetailRepo;

    /**
     *
     * @var OrderPaymentRepo
     */
    protected $orderPaymentRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VtexApiService $vtexService, OrderRepo $orderRepo, OrderDetailRepo $orderDetailRepo,
        OrderPaymentRepo $orderPaymentRepo) {

        parent::__construct();
        $this->vtexService = $vtexService;
        $this->orderRepo = $orderRepo;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->orderPaymentRepo = $orderPaymentRepo;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = $this->vtexService->getData();

        foreach ($data as $key => $value) {

            $order = $this->orderRepo->store($value['order']);
            $order_detail = $this->orderDetailRepo->store($order, $value['items']);
            $order_payment = $this->orderPaymentRepo->store($order, $value['payment_data']);
        }

        dd("temrihno");

    }
}
