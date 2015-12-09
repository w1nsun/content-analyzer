<?php

namespace app\controllers;

use app\models\forms\SignupForm;
use app\models\User;
use GuzzleHttp\Client;
use Yii;
use yii\authclient\OAuthToken;
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
        $httpClient = new Client();
        // Создаем OAuthToken
        $token = new OAuthToken([
            'token' => Yii::$app->params['twitter_api']['access_token'],
            'tokenSecret' => Yii::$app->params['twitter_api']['token_secret']
        ]);
        $socialClient = new \yii\authclient\clients\Twitter([
            'accessToken'    => $token,
            'consumerKey'    => Yii::$app->params['twitter_api']['consumer_key'],
            'consumerSecret' => Yii::$app->params['twitter_api']['consumer_secret'],
        ]);
        $twitterTrends = new \app\components\trends\Twitter($httpClient, $socialClient);
        $result = $twitterTrends->find('test');

        vd($result);


//        $response = $client->request('GET', 'https://api.twitter.com/1.1/trends/place.json?id=918981', [
//            'headers' => [
//                'Authorization' => 'OAuth oauth_consumer_key="5Pz2Po9yRyMKv5sp1I3nrYi1h",
//                oauth_nonce="2edc180e0776d1600ba2ea33907c3c13",
//                oauth_signature="8v2Gx7%2FSunkAGFIFoigbKIVdjeE%3D",
//                oauth_signature_method="HMAC-SHA1",
//                oauth_timestamp="1449666578",
//                oauth_token="1682905063-qJLytt0EJBTgFRicPD9Fw0yOngh6nEeMeODCzOz",
//                oauth_version="1.0"'
//            ]
//        ]);

//        $client = new Client();
//        $response = $client->request('GET', 'https://api.twitter.com/1.1/search/tweets.json?q=%23коррупция&include_entities=1&result_type=mixed&count=4', [
//            'headers' => [
//                'Authorization' => 'OAuth oauth_consumer_key="5Pz2Po9yRyMKv5sp1I3nrYi1h", oauth_nonce="7ed2d0a0fdaf671ef2556d82668f0e50", oauth_signature="bMjOG4KE2ArSwiw7d7d4aJ7tC88%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1449667379", oauth_token="1682905063-qJLytt0EJBTgFRicPD9Fw0yOngh6nEeMeODCzOz", oauth_version="1.0"'
//            ]
//        ]);
//
//        vd(json_decode($response->getBody()->getContents(), true));

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

            $user           = new User();
            $user->email    = $model->email;
            $user->password = $model->password;

            $user->register();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Вы успешно зарегистрированы'));

            return $this->refresh();
        }

        return $this->render('sign_up_form', [
            'model' => $model
        ]);
    }
}
