<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;

class OrderDetailRepo
{
    /**
     * @return OrderDetail|mixed
     */
    public function getModel()
    {
        return new OrderDetail();
    }

    /**
     * @return OrderDetail|mixed
     */
    public function store(Order $order, array $array)
    {
        $array_details = [];

        foreach ($array as $key => $value) {

            array_push($array_details,
                [
                    'order_id' => $order->id,
                    'product_id' => $value['product_id'],
                    'ref_id' => $value['ref_id'],
                    'quantity' => $value['quantity'],
                    'name' => $value['name']
                ]);
        }

        OrderDetail::insert($array_details);

        return $order;
    }
}
