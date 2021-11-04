<?php

namespace App\Services;

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
     * @param GuzzleClient $client
     */
    public function __construct(GuzzleClient $client, OrderRepo $orderRepo)
    {
        $this->client = $client;
        $this->orderRepo = $orderRepo;
        $this->url_orders_list = env('VTEX_ENDPOINT_GET_ORDER');
        $this->url_order_detail = env('VTEX_ENDPOINT_GET_ORDER_DETAIL');
        $this->header = config('vtex.headers');

    }

    public function getData()
    {

        $date_one = gmDate("2016-01-01\T02:00:00\.000\Z");
        $date_two = gmDate("2021-01-01\T01:59:59\.999\Z");
        //date_format(date_create('01 Jan 2021'), 'c');

        $query = [
            'f_creationDate' => "creationDate:[{$date_one} TO {$date_two}]",
            'f_hasInputInvoice' => 'false'
        ];

        //f_creationDate=creationDate%3A%5B2016-01-01T02%3A00%3A00.000Z%20TO%202021-01-01T01%3A59%3A59.999Z%5D&f_hasInputInvoice=false

        $response = $this->client->request('GET', $this->url_orders_list,

            ['headers' => $this->header,
                'query' => $query

            ]);

        $data = $response->getBody()->getContents();

        $array_order_list = json_decode($data, true);

        //dump($array_order_list);

        foreach ($array_order_list['list'] as $key => $value) {

            dump($value['orderId']);
        }

    }

}
