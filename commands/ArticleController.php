<?php

namespace app\commands;

use app\components\ArticleScoreWorker;
use GuzzleHttp\Client;
use yii\console\Controller;
use yii\di\ServiceLocator;

class ArticleController extends Controller
{
    public function actionParseLikes()
    {
        $client         = new Client();
        $serviceLocator = new ServiceLocator();
        $worker         = new ArticleScoreWorker($client, $serviceLocator);

        $worker->run();

        echo "Parsed!\n";
        return 0;
    }
}