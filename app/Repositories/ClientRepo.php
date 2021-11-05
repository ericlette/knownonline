<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepo
{
    /**
     * @var array $array
     * @return Client|mixed
     */
    public function store(array $array)
    {
        $client = Client::firstOrCreate([
            'number_client' => $array[0]['number_client'],
            'firstname' => $array[0]['firstname'],
            'lastname' => $array[0]['lastname'],
            'email' => $array[0]['email']
        ]);

        return $client;
    }
}
