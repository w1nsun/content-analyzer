<?php

namespace app\components\trends;

use GuzzleHttp\Client;

class Twitter
{
    protected $httpClient;
    protected $socialClient;

    public function __construct(Client $httpClient, \yii\authclient\clients\Twitter $socialClient)
    {
        $this->httpClient = $httpClient;
        $this->socialClient = $socialClient;
    }

    public function find($query)
    {
        return $this->socialClient->api('search/tweets.json', 'GET', [
            'q' => $query,
//            'include_entities' => 1,
            'result_type' => 'mixed',
            'count' => 20
        ]);
    }
}