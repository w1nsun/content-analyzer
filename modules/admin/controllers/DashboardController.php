<?php

namespace app\modules\admin\controllers;

use yii\base\Event;

class DashboardController extends BaseController
{
    /**
     * Action
     * @return string
     */
    public function actionIndex()
    {
//        \Yii::$app->trigger('test.event', new Event(['sender' => 'sender1']));
        return $this->render('index');
    }
}
