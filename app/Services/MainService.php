<?php

namespace App\Services;

use App\Repositories\ClientRepo;
use App\Repositories\OrderDetailRepo;
use App\Repositories\OrderPaymentRepo;
use App\Repositories\OrderRepo;
use App\Services\VtexApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MainService
{
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
     *
     * @var ClientRepo
     */
    protected $clientRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VtexApiService $vtexService, OrderRepo $orderRepo, OrderDetailRepo $orderDetailRepo,
        OrderPaymentRepo $orderPaymentRepo, ClientRepo $clientRepo) {

        $this->vtexService = $vtexService;
        $this->orderRepo = $orderRepo;
        $this->orderDetailRepo = $orderDetailRepo;
        $this->orderPaymentRepo = $orderPaymentRepo;
        $this->clientRepo = $clientRepo;

    }

    /**
     *
     * @return Boolean $result;
     */
    public function handle()
    {
        $data = $this->vtexService->getData();
        $result = true;

        foreach ($data as $key => $value) {

            DB::beginTransaction();

            try {
                $client = $this->clientRepo->store($value['data_client']);
                $order = $this->orderRepo->store($value['order'], $client);
                $order_detail = $this->orderDetailRepo->store($order, $value['items']);
                $order_payment = $this->orderPaymentRepo->store($order, $value['payment_data']);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();

                Log::error('The test failed because : ' . $e->getMessage());
                $result = false;

            }

        }
        return $result;
    }

}
