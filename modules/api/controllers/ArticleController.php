<?php

namespace app\modules\api\controllers;

use app\components\Images\ImageDownloader;
use app\components\Images\ImageValidator;
use app\models\Article;
use app\models\Image;
use yii\data\ActiveDataProvider;

class ArticleController extends Controller
{
    public $modelClass = 'app\models\Article';
    protected $imagePath = 'files';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

    public function actionIndex()
    {
        $provider = new ActiveDataProvider([
            'query' => Article::find()->where(['status' => Article::STATUS_ACTIVE]),
            'pagination' => [
                'pageSize' => static::ITEMS_PER_PAGE,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        return $provider;
    }

    public function actionCreate()
    {
        $request = \Yii::$app->getRequest();
        $article = $this->loadByUrlOrNew(\Yii::$app->request->post('Article')['url']);

        if ($article->load($request->post())) {

            if($article->getIsNewRecord()){
                $article->type   = Article::TYPE_ARTICLE;
                $article->status = Article::STATUS_ACTIVE;
            }

            if ($article->save()) {

                if (isset($request->post('Article')['image'])) {
                    $imageUrl = $request->post('Article')['image'];
                    $imageFile= $this->saveArticleImage($imageUrl, $article);

                    if ($imageFile !== false) {

                        list($width, $height) = getimagesize($this->getFullImagePath() . '/' . $imageFile);

                        $image = new Image(['scenario' => Image::SCENARIO_CREATE]);
                        $image->owner    = Image::OWNER_ARTICLE;
                        $image->owner_id = $article->id;
                        $image->src      = $image;
                        $image->width    = $width;
                        $image->height   = $height;
                        $image->size     = Image::SIZE_ORIGINAL;
                        $image->status   = Image::STATUS_ACTIVE;

                        $image->save();
                    }
                }

                return ['id' => $article->id];
            }
        }

        $response = \Yii::$app->getResponse();
        $response->setStatusCode(500);
        $response->statusText ='Internal Server Error';

        return ['errors' => $article->getErrors()];
    }

    protected function loadByUrlOrNew($url)
    {
        $article = Article::findOne(['url' => $url]);

        if ($article) {
            $article->setScenario(Article::SCENARIO_UPDATE);
            return $article;
        }

        return new Article(['scenario' => Article::SCENARIO_CREATE]);
    }

    protected function saveArticleImage($imageUrl, Article $article)
    {
        $validatorParams = [
            'allowedTypes' => IMAGETYPE_BMP, IMAGETYPE_JPEG, IMAGETYPE_PNG
        ];

        $imageValidator = new ImageValidator($validatorParams);
        $tempDir = \Yii::getAlias('@app/runtime/tmp');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0775);
        }

        if (!is_writable($tempDir)) {
            chmod($tempDir, 0775);
        }

        $imageDownloader = new ImageDownloader($imageValidator, $tempDir);
        $filename        = uniqid('article_image') . '.' . pathinfo($imageUrl, PATHINFO_EXTENSION);
        $fullPathToSave  = $this->getFullImagePath();

        if (!file_exists($fullPathToSave)) {
            mkdir($fullPathToSave, 0777);
        }

        if (!is_writable($fullPathToSave)) {
            chmod($fullPathToSave, 0777);
        }

        $result = $imageDownloader
                    ->from($imageUrl)
                    ->to($fullPathToSave . '/' . $filename)
                    ->save();

        if (!$result) {
            return false;
        }

        return $filename;
    }

    protected function getFullImagePath()
    {
        return \Yii::getAlias('@app/web/' . $this->imagePath);
    }
}
