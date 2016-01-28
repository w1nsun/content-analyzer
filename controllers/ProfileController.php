<?php

namespace app\controllers;

use yii\web\Controller;

class ProfileController extends Controller
{
    public $layout = '@app/views/layouts/profile';

    public function actionIndex()
    {
        return $this->render('index');
    }
}