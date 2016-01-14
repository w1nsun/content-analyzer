<?php

namespace app\controllers;

use app\models\forms\SignupForm;
use app\models\LikesLog;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user               = new User();
            $user->email        = $model->email;
            $user->password     = $model->password;
            $user->access_token = '';

            $user->register();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Вы успешно зарегистрированы'));

            return $this->refresh();
        }

        return $this->render('sign_up_form', [
            'model' => $model
        ]);
    }

    public function actionTrends()
    {
        $likesTable = LikesLog::tableName();
        $totalQuery = '`facebook` + `twitter` + `pinterest` + `linkedin` + `google_plus` + `vkontakte`';
        $joinTable = "(SELECT article_id, MAX(created_at) AS mcreated_at FROM $likesTable GROUP BY article_id) AS tmp";
        $joinOn =  "$likesTable.article_id = tmp.article_id AND $likesTable.created_at = mcreated_at";

        $dataProvider = new ActiveDataProvider([
            'query' => LikesLog::find()->innerJoin($joinTable, $joinOn)->joinWith(['article']),
            'sort'  => [
                'attributes' => [
                    'article.title', 'facebook', 'twitter', 'pinterest', 'linkedin', 'google_plus', 'vkontakte',
                    'total' => [
                        'asc'   => ['created_at' => SORT_ASC, $totalQuery => SORT_ASC],
                        'desc'  => ['created_at' => SORT_DESC, $totalQuery => SORT_DESC],
                    ],
                ],
                'defaultOrder' => [
                    'total' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('trends', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
