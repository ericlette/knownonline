<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Order;

class OrderRepo
{
    /**
     * @return Order|mixed
     */
    public function getModel()
    {
        return new Order();
    }

    /**
     * @return Order|mixed
     */
    public function store(array $array, Client $client)
    {

        $order = $this->getModel();
        $order->number_order = $array['order_id'];
        $order->total = $array['importe_total'];
        $order->client_id = $client->id;
        $order->save();

        return $order;
    }
}
