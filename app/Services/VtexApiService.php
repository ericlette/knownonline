<?php

namespace App\Services;

use App\Http\Helpers\ParseHelper;
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
     * @var ParseHelper
     */

    private $parseHelper;

    /**
     * @param GuzzleClient $client
     */

    public function __construct(GuzzleClient $client, OrderRepo $orderRepo, ParseHelper $parseHelper)
    {
        $this->client = $client;
        $this->orderRepo = $orderRepo;
        $this->url_orders_list = env('VTEX_ENDPOINT_GET_ORDER');
        $this->url_order_detail = env('VTEX_ENDPOINT_GET_ORDER_DETAIL');
        $this->header = config('vtex.headers');
        $this->parseHelper = $parseHelper;

    }

    /**
     * @return array
     */
    public function getData()
    {
        $date_start = "2021-01-01 02:00:00";
        $date_end = date('Y-m-d H:i:s');
        $status = 'ready-for-handling';

        $query = $this->parseHelper->parseParams($date_start, $date_end, $status);

        $array_order_details = [];
        foreach ($this->getOrderList($query) as $key => $value) {

            $order = $this->getOrder($value['orderId']);
            array_push($array_order_details, $this->parseHelper->parseArray($value['orderId'], $value['totalValue'], $order));

        }

        return $array_order_details;
    }

    /**
     * @param array $query
     * @return array
     */
    protected function getOrderList(array $query)
    {
        $response = $this->client->request('GET', $this->url_orders_list,
            ['headers' => $this->header, 'query' => $query]);
        $array_order_list = json_decode($response->getBody()->getContents(), true);

        return $array_order_list['list'];
    }

    /**
     * @param string $order_id
     * @return array $order
     */
    protected function getOrder(string $order_id)
    {
        $response = $this->client->request('GET', $this->url_order_detail . $order_id,
            ['headers' => $this->header]);
        $order = json_decode($response->getBody()->getContents(), true);

        return $order;
    }

}
