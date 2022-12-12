<?php

namespace App\Domain\Client\Service;

use GuzzleHttp\Client as Client;


class HttpClientService
{

    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'https://stooq.com/q/l';

    }

    public function getStockInfo($stock)
    {
        $client = new Client();
        $guzzle = $client->request('GET', $this->apiUrl , [
            'query' => [
                's' => $stock,
                'e' => 'json',
                'f' => 'sd2t2ohlcvn',
            ]
        ]);

       return json_decode($guzzle->getBody()->getContents());
    }


}
