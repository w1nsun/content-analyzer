<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../components/Application.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new app\components\Application($config);

function vd($var, $exit = true)
{
    \yii\helpers\BaseVarDumper::dump($var, 10, true);
    if ($exit)
        exit;
}

$application->registerProviders();
$application->run();
