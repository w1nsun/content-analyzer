<?php

namespace app\controllers;

use app\components\UserTokenManager;
use app\models\User;
use yii\web\Controller;

class ProfileController extends Controller
{
    public $layout = '@app/views/layouts/profile';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow'   => true,
                        'actions' => [
                            'index',
                            'generate-access-token'
                        ],
                        'roles'   => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = $this->findUser();

        return $this->render('index', [
            'user' => $user
        ]);
    }

    public function actionGenerateAccessToken()
    {
        $tokenManager = new UserTokenManager($this->findUser());

        if ($tokenManager->updateToken()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Токен успешно обновлен'));
        } else {
            \Yii::$app->getSession()->setFlash('danger', \Yii::t('app', 'Ошибка при генерации токена'));
        }

        $this->redirect('/profile');
    }

    /**
     * @return null|User
     */
    protected function findUser()
    {
        return User::findOne(\Yii::$app->getUser()->getId());
    }
}