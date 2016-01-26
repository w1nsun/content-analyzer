<?php

namespace app\controllers;

use app\models\Article;
use app\models\forms\SignupForm;
use app\models\User;
use Yii;
use yii\authclient\BaseClient;
use yii\authclient\OAuth2;
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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
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
        $model->setScenario(SignupForm::SCENARIO_DEFAULT);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $user               = new User();
            $user->email        = $model->email;
            $user->password     = $model->password;

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
        return $this->render('trends', [
            'trends' => Article::find()->trends(),
        ]);
    }

    public function successCallback(BaseClient $client)
    {
        $attributes = $client->getUserAttributes();
        $signUpForm = new SignupForm();
        $signUpForm->setScenario(SignupForm::SCENARIO_SOCIAL);
        $signUpForm->email = $attributes['email'];

        if ($signUpForm->validate()) {
            $user              = new User();
            $user->email       = $signUpForm->email;
            $user->password    = md5(time());
            $user->social_id   = $attributes['id'];
            $user->social_name = $client->getId();

            $user->register();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Вы успешно зарегистрированы'));
        } else {
            $errors = $signUpForm->getErrors();
            $error = reset($errors)[0];
            Yii::$app->session->setFlash('danger', $error);
        }

        return false;
    }
}
