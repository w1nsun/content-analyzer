<?php

namespace app\components;

use yii\base\Component;
use yii\base\Event;

class EventHandlers extends Component
{
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->register();
    }

    protected function register()
    {
        \Yii::$app->on('test.event', function (Event $event) {
            vd($event);
        });
    }

}