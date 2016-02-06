<?php

namespace app\commands;

use app\components\ArticleLikesWorker;
use app\components\LikesLog;
use GuzzleHttp\Client;
use yii\console\Controller;
use yii\db\Query;
use yii\di\ServiceLocator;

class ArticleController extends Controller
{
    public function actionParseLikes()
    {
        $client         = new Client();
        $serviceLocator = new ServiceLocator();
        $likesLog       = new LikesLog(\Yii::$app->db, new Query());
        $worker         = new ArticleLikesWorker($client, $serviceLocator, $likesLog);

        $worker->run();

        echo "Parsed!\n";
        return 0;
    }
}