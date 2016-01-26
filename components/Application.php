<?php

namespace app\components;

class Application extends \yii\web\Application
{
    public function registerProviders()
    {
        $container = \Yii::$container;
    }
}