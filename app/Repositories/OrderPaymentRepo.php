<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\PaymentMethod;

class OrderPaymentRepo
{

    /**
     * @return Order|mixed
     */
    public function store(Order $order, array $paymentMethod)
    {

        foreach ($paymentMethod as $key => $value) {

            $paymentMethod = PaymentMethod::firstOrCreate([
                'payment_system' => $value['payment_system'],
                'payment_system_name' => $value['payment_system_name']
            ]);

            $order->payments()->attach($paymentMethod->id, ['payment_id' => $value['id']]);
        }

        return $order;
    }
}
