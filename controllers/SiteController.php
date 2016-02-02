<?php

namespace app\controllers;

use app\components\image\ImageManager;
use app\models\Article;
use app\models\forms\SignupForm;
use app\models\User;
use Elasticsearch\Client;
use Yii;
use yii\authclient\BaseClient;
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
                'successCallback' => [$this, 'successAuthCallback'],
            ],
            'social-signup' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successSignUpCallback'],
            ],
        ];
    }

    public function actionIndex()
    {
//        $im = new ImageManager('/var/www/analyzer/web/files/1/0/9/article56ab3901343bd.jpg');
//        $im->addSize(300, 200)->addSize(200, 300)->addSize(2000, 1560)->doThumbnail();

//http://www.slideshare.net/AltorosBY/elasticsearch-26227465
        //https://gist.github.com/svartalf/4465752
        //https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lang-analyzer.html
        //https://github.com/imotov/elasticsearch-analysis-morphology
        /** @var Client $es */
        $es = Yii::$container->get('elasticsearch');
//        $params = [
//            'index' => 'analyzer_article',
//            'body' => [
//                'settings' => [
//                    'number_of_shards' => 1,
//                    'number_of_replicas' => 0,
//                    'analysis' => [
//                        'filter' => [
//                            'russian_stop' => [
//                                'type' => 'stop',
//                                'stopwords' => 'а,без,более,бы,был,была,были,было,быть,в,вам,вас,весь,во,вот,все,'.
//                                                'всего,всех,вы,где,да,даже,для,до,его,ее,если,есть,еще,же,за,здесь,и,'.
//                                                'из,или,им,их,к,как,ко,когда,кто,ли,либо,мне,может,мы,на,надо,наш,не,'.
//                                                'него,нее,нет,ни,них,но,ну,о,об,однако,он,она,они,оно,от,очень,по,под,'.
//                                                'при,с,со,так,также,такой,там,те,тем,то,того,тоже,той,только,том,ты,у,'.
//                                                'уже,хотя,чего,чей,чем,что,чтобы,чье,чья,эта,эти,это,я,a,an,and,are,'.
//                                                'as,at,be,but,by,for,if,in,into,is,it,no,not,of,on,or,such,that,the'.
//                                                ',their,then,there,these,they,this,to,was,will,with'
//                            ],
////                            'russian_keywords' => [
////                                'type' => 'keyword_marker',
////                                'keywords' => []
////                            ],
//                            'russian_stemmer' => [
//                                'type' => 'stemmer',
//                                'language' => 'russian'
//                            ]
//                        ],
//                        'analyzer' => [
//                            'russian' => [
//                                'tokenizer'=>  'standard',
//                                'char_filter' => ['html_strip'],
//                                'filter'=> [
//                                    'lowercase',
//                                    'russian_stop',
////                                    'russian_morphology',
////                                    'russian_keywords',
//                                    'russian_stemmer'
//                                ]
//                            ]
//                        ]
//                    ]
//                ],
//                'mappings' => [
//                    'analyzer_article' => [
//                        '_source' => [
//                            'enabled' => true
//                        ],
//                        'properties' => [
//                            'title' => [
//                                'type' => 'string',
//                                'analyzer' => 'russian'
//                            ],
//                            'description' => [
//                                'type' => 'string',
//                                'analyzer' => 'russian'
//                            ],
//                        ]
//                    ]
//                ]
//            ]
//        ];
//        $es->indices()->create($params);

//        $params = [
//            'index' => 'analyzer_article',
//            'type' => 'analyzer_article',
//            'id' => '1',
//            'body' => ['title' => 'Заголовок', 'description' => 'Маше купили футбольный мяч']
//        ];
//        $response = $es->index($params);
//        $params = [
//            'index' => 'analyzer_article',
//            'type' => 'analyzer_article',
//            'body' => [
//                'query' => [
//                    'match' => [
////                        'title' => 'мяч',
//                        'description' => 'футбол',
//                    ]
//                ]
//            ]
//        ];
//
//        $response = $es->search($params);

//        vd($response);

        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(['scenario' => LoginForm::SCENARIO_DEFAULT]);
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

    public function successSignUpCallback(BaseClient $client)
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


    public function successAuthCallback(BaseClient $client)
    {
        $attributes = $client->getUserAttributes();
        $loginForm = new LoginForm(['scenario' => LoginForm::SCENARIO_SOCIAL]);
        $loginForm->social = $client->getId();
        $loginForm->social_id = $attributes['id'];

        if (!$loginForm->socialLogin()) {
            $errors = $loginForm->getErrors();
            $error = reset($errors)[0];
            Yii::$app->session->setFlash('danger', $error);
        }

        return false;
    }
}
