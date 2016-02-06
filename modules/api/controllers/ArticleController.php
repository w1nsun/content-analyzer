<?php

namespace app\modules\api\controllers;

use app\components\FileSystem;
use app\components\image\ImageDownloader;
use app\components\image\ImageValidator;
use app\models\Article;
use app\models\Image;
use app\models\Tag;
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

        //todo: разделить загрузку тэгов и картинок в отдельный контроллер

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
                $this->extractAndSaveTags($request, $article->id);
                if (!empty($request->post('image'))) {
                    $this->attachImage($request->post('image'), $article);
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
    protected function attachImage($imageUrl, Article $article)
    {
        /** @var FileSystem $fileSystem */
        $fileSystem = \Yii::$app->fs;
        $validator  = new ImageValidator();
        $downloader = new ImageDownloader($validator, $fileSystem);
        $imagePath  = $fileSystem->generateDir() . '/' . uniqid('article') . '.' . Image::extractExtFromUrl($imageUrl);
        $imageFile  = $fileSystem->getFsDir() . '/' . $imagePath;
        $result     = $downloader->from($imageUrl)->to($imageFile)->download();
        list($imageWidth, $imageHeight) = getimagesize($imageFile);

        if ($result) {
            $image             = new Image(['scenario' => Image::SCENARIO_CREATE]);
            $image->owner_type = Image::OWNER_TYPE_ARTICLE;
            $image->owner_id   = $article->id;
            $image->size       = Image::SIZE_ORIGINAL;
            $image->src        = $imagePath;
            $image->width      = $imageWidth;
            $image->height     = $imageHeight;
            $image->status     = Image::STATUS_ACTIVE;

            $image->save();
        }
    }

    /**
     * @param \yii\web\Request $request
     * @param int $articleId
     * @return null
     */
    protected function extractAndSaveTags(\yii\web\Request $request, $articleId)
    {
        $tags = json_decode($request->post()['tags']);

        if (empty($tags)) {
            return null;
        }

        $tags  = array_map('strtolower', $tags);
        $tags  = array_unique(array_map('trim', $tags));
        $tagQuery = Tag::find();

        $tagQuery->batchAdd($tags);


        //todo: разделить функционал

        /** @var \app\models\Tag[] $tags */
        $tags         = $tagQuery->select('id')->where(['tag' => $tags])->distinct()->all();
        $articleQuery = Article::find();

        $tagsIds = [];
        foreach ($tags as $tag) {
            $tagsIds[] = $tag->id;
        }

        $articleQuery->batchAddRelativeTags($articleId, $tagsIds);
    }
}
