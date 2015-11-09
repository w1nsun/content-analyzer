<?php

namespace app\modules\api\controllers;

use app\components\FileSystem;
use app\components\image\ImageDownloader;
use app\components\image\ImageValidator;
use app\models\Article;
use app\models\Image;
use yii\data\ActiveDataProvider;

class ArticleController extends Controller
{
    /**
     * @var string
     */
    public $modelClass = 'app\models\Article';

    /**
     * @var string
     */
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
        $article = $this->loadByUrlOrNew($request->post('Article')['url']);

        if ($article->load($request->post())) {

            if($article->getIsNewRecord()){
                $article->type        = Article::TYPE_ARTICLE;
                $article->status      = Article::STATUS_ACTIVE;
                //todo: заменить на код
                $article->category_id = 0;
            }

            if ($article->save()) {
                if (!empty($request->post('image'))) {
                    $this->saveImage($request->post('image'), $article);
                }

                return ['id' => $article->id];
            }
        }

        $response = \Yii::$app->getResponse();
        $response->statusText ='Internal Server Error';
        $response->setStatusCode(500);

        return ['errors' => $article->getErrors()];
    }

    /**
     * @param $url
     * @return Article
     */
    protected function loadByUrlOrNew($url)
    {
        /** @var Article $article */
        $article = Article::findOne(['url' => $url]);

        if ($article) {
            $article->setScenario(Article::SCENARIO_UPDATE);
            return $article;
        }

        return new Article(['scenario' => Article::SCENARIO_CREATE]);
    }

    /**
     * @param $imageUrl
     * @param Article $article
     */
    protected function saveImage($imageUrl, Article $article)
    {
        /** @var FileSystem $fileSystem */
        $fileSystem = \Yii::$app->fs;
        $validator  = new ImageValidator();
        $downloader = new ImageDownloader($validator, $fileSystem);
        $imagePaths = $this->generateSaveImagePath($imageUrl);
        $result     = $downloader
                        ->from($imageUrl)
                        ->to($imagePaths['full_path'])
                        ->download();

        if ($result) {
            $image             = new Image(['scenario' => Image::SCENARIO_CREATE]);
            $image->owner_type = Image::OWNER_TYPE_ARTICLE;
            $image->owner_id   = $article->id;
            $image->size       = Image::SIZE_ORIGINAL;
            $image->src        = $imagePaths['relative_path'];
            $image->width      = $fileSystem->image()->file($imagePaths['full_path'])->getWidth();
            $image->height     = $fileSystem->image()->file($imagePaths['full_path'])->getHeight();
            $image->status     = Image::STATUS_ACTIVE;

            $image->save();
        }

    }

    /**
     * @param string $imageUrl
     * @return string
     */
    protected function generateSaveImagePath($imageUrl)
    {
        /** @var \app\components\FileSystem $fileSystem */
        $fileSystem = \Yii::$app->fs;
        $subDir     = $fileSystem->image()->createSubDirs(uniqid());
        $imagesDir  = $fileSystem->image()->getDir();
        $imageExt   = pathinfo($imageUrl, PATHINFO_EXTENSION);
        $imageName  = uniqid('article_') . '.' . $imageExt;

        return [
            'full_path'     => $imagesDir . '/' . $subDir . '/' . $imageName,
            'relative_path' => $subDir . '/' . $imageName,
        ];
    }
}
