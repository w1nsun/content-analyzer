<?php

namespace app\controllers;

use app\models\User;
use yii\web\Controller;

class ProfileController extends Controller
{
    public $layout = '@app/views/layouts/profile';

    public function actionIndex()
    {
        $user = User::findOne(\Yii::$app->getUser()->getId());

        return $this->render('index', [
            'user' => $user
        ]);
    }
}