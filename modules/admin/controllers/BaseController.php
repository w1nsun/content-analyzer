<?php


namespace app\modules\admin\controllers;

use app\components\Roles;
use yii\web\Controller;
use yii\filters\AccessControl;

class BaseController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => [Roles::ADMIN],
                    ],
                    // everything else is denied by default
                ],
            ],
        ];
    }

}