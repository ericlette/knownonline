<?php

namespace App\Repositories;

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
    public function store()
    {

        $Order = $this->getModel();
        $Order->save();

        return $Order;
    }
}
