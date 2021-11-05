<?php

return [

    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'X-VTEX-API-AppKey' => env('VTEX_API_KEY'),
        'X-VTEX-API-AppToken' => env('VTEX_TOKEN')
    ]

];
