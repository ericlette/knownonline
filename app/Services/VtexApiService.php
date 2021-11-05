<?php

namespace App\Services;

use App\Http\Helpers\ParseParamsHelper;
use App\Models\Order;
use App\Repositories\OrderRepo;
use GuzzleHttp\Client as GuzzleClient;

class VtexApiService
{
    /**
     * @var
     */
    private $url_orders_list;

    /**
     * @var
     */
    private $url_order_detail;

    /**
     * @var
     */
    private $header;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @var OrderRepo
     */
    private $orderRepo;

    /**
     * @var ParseParamsHelper
     */

    private $parseHelper;

    /**
     * @param GuzzleClient $client
     */

    public function __construct(GuzzleClient $client, OrderRepo $orderRepo, ParseParamsHelper $parseHelper)
    {
        $this->client = $client;
        $this->orderRepo = $orderRepo;
        $this->url_orders_list = env('VTEX_ENDPOINT_GET_ORDER');
        $this->url_order_detail = env('VTEX_ENDPOINT_GET_ORDER_DETAIL');
        $this->header = config('vtex.headers');
        $this->parseHelper = $parseHelper;

    }

    public function getData()
    {
        $date_start = "2021-01-01 03:45:27.612584";
        $date_end = date('Y-m-d H:i:s.612584');
        $status = 'ready-for-handling';

        $query = $this->parseHelper->parseParams($date_start, $date_end, $status);

        $array = [];
        foreach ($this->getOrderList($query) as $key => $value) {

            $order = $this->getOrder($value['orderId']);
            array_push($array, $this->parseHelper->parseArray($value['orderId'], $order));

        }

        return $array;
    }

    protected function getOrderList(array $query): array
    {
        $response = $this->client->request('GET', $this->url_orders_list,
            ['headers' => $this->header, 'query' => $query]);
        $array_order_list = json_decode($response->getBody()->getContents(), true);

        return $array_order_list['list'];
    }

    protected function getOrder($order_id): array
    {
        $response = $this->client->request('GET', $this->url_order_detail . $order_id,
            ['headers' => $this->header]);
        $order = json_decode($response->getBody()->getContents(), true);

        return $order;
    }

}
