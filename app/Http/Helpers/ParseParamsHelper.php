<?php

namespace App\Http\Helpers;

use Carbon\Carbon;

class ParseParamsHelper
{

    /**
     * @param String $date_start
     * @param String $date_end
     * @param String $status
     * @return array
     */
    public function parseParams(String $date_start, String $date_end, String $status): array
    {

        $date_one = Carbon::createFromFormat('Y-m-d H:i:s.u', $date_start)->toISOString();
        $date_two = Carbon::createFromFormat('Y-m-d H:i:s.u', $date_end)->toISOString();

        $query = [
            'f_creationDate' => "creationDate:[{$date_one} TO {$date_two}]",
            'f_hasInputInvoice' => 'false',
            'f_status' => $status
        ];

        return $query;

    }

    /**
     * @param String $order_id
     * @param array $array
     * @return array
     */
    public function parseArray(string $order_id, array $array): array
    {

        $payload = [
            'order' => [
                'order_id' => $order_id,
                'importe_total' => $this->parseTotal($array['totals'])
            ],
            'items' => $this->parseItems($array['items']),
            'payment_data' => $this->parsePayment($array['paymentData']['transactions']),
            'data_client' => $this->parseClients($array['clientProfileData'])
        ];

        return $payload;
    }

    /**
     * @param array $array
     * @return float
     */

    protected function parseTotal(array $totals): float
    {
        $total = 0;
        foreach ($totals as $key => $value) {
            $total += $value['value'];
        }

        return $total;
    }

    /**
     * @param array $array
     * @return array
     */
    protected function parseItems(array $items): array
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
     * @param array $array
     * @return array
     */
    protected function parsePayment(array $payment): array
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
     * @param array $array
     * @return array
     */
    protected function parseClients(array $client): array
    {
        $array = [];

        array_push($array,
            [
                'id' => $client['id'],
                'lastname' => $client['lastName'],
                'firstname' => $client['firstName'],
                'documentType' => $client['documentType'],
                'document' => $client['document'],
                'phone' => $client['phone']
            ]);

        return $array;
    }

}
