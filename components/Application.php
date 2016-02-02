<?php

namespace app\components;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use yii\di\Container;

class Application extends \yii\web\Application
{
    public function registerProviders()
    {
        /** @var Container $container */
        $container = \Yii::$container;
        $container->setSingleton('elasticsearch', function() {
            /** @var Client $client */
            $client = ClientBuilder::create()->build();
            return $client;
        });
    }
}