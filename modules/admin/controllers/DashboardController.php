<?php

namespace app\modules\admin\controllers;

class DashboardController extends BaseController
{
    /**
     * Action
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
