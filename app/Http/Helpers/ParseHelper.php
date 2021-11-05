<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class ParseHelper
{
    /**
     * @param String $date_start
     * @param String $date_end
     * @param String $status
     * @return array
     */
    public function parseParams(String $date_start, String $date_end, String $status)
    {
        $date_one = Carbon::parse($date_start)->toISOString();
        $date_two = Carbon::parse($date_end)->toISOString();

        $query = [
            'f_creationDate' => "creationDate:[{$date_one} TO {$date_two}]",
            'f_hasInputInvoice' => 'false',
            'f_status' => $status
        ];

        return $query;
    }

    /**
     * @param string $order_id
     * @param string $valueTotal
     * @param array $array
     * @return array
     */
    public function parseArray(string $order_id, string $valueTotal, array $array)
    {
        $payload = [
            'order' => [
                'order_id' => $order_id,
                'importe_total' => $valueTotal
            ],
            'items' => $this->parseItems($array['items']),
            'payment_data' => $this->parsePayment($array['paymentData']['transactions']),
            'data_client' => $this->parseClients($array['clientProfileData'])
        ];

        return $payload;
    }

    /**
     * @param array $array
     * @return array
     */
    protected function parseItems(array $items)
    {
        $array = [];
        foreach ($items as $key => $value) {

            array_push($array,
                [
                    'product_id' => $value['productId'],
                    'name' => $value['name'],
                    'quantity' => $value['quantity'],
                    'ref_id' => $value['refId']
                ]);
        }
        return $array;
    }

    /**
     * @param array $payment
     * @return array
     */
    protected function parsePayment(array $payment)
    {
        $array = [];
        $cont = 0;
        foreach ($payment as $key => $value) {

            array_push($array,
                [
                    'id' => $value['payments'][$cont]['id'],
                    'payment_system' => $value['payments'][$cont]['paymentSystem'],
                    'payment_system_name' => $value['payments'][$cont]['paymentSystemName']

                ]);
            $cont++;
        }
        return $array;
    }

    /**
     * @param array $client
     * @return array
     */
    protected function parseClients(array $client)
    {
        $array = [];
        array_push($array,
            [
                'number_client' => $client['id'],
                'lastname' => $client['lastName'],
                'firstname' => $client['firstName'],
                'email' => $client['email']
            ]);

        return $array;
    }

}
