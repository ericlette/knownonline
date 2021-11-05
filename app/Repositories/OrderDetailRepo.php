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

        foreach ($array as $key => $value) {

            $order_detail = $this->getModel();

            $order_detail->order_id = $order->id;
            $order_detail->product_id = $value['product_id'];
            $order_detail->ref_id = $value['ref_id'];
            $order_detail->quantity = $value['quantity'];
            $order_detail->name = $value['name'];

            $order_detail->save();
        }

        return $order;
    }
}
