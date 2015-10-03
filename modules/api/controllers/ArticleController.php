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

                if (isset($request->post('Article')['image']) && !empty($request->post('Article')['image'])) {

                    $image             = new Image(['scenario' => Image::SCENARIO_CREATE]);
                    $image->owner_type = Image::OWNER_TYPE_ARTICLE;
                    $image->owner_id   = $article->id;
                    $image->size       = Image::SIZE_ORIGINAL;
                    $image->status     = Image::STATUS_DELETED;

                    $image->save();

                    $imageUrl  = trim($request->post('Article')['image']);
                    $imageFile = $this->saveArticleImage($imageUrl, $image);

                    if ($imageFile === false) {
                        $image->delete();
                    } else {
                        list($width, $height) = getimagesize($this->getFullImagePath() . '/' . $imageFile);

                        $image->src        = $imageFile;
                        $image->width      = $width;
                        $image->height     = $height;
                        $image->status     = Image::STATUS_ACTIVE;

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
        /** @var Article $article */
        $article = Article::findOne(['url' => $url]);

        if ($article) {
            $article->setScenario(Article::SCENARIO_UPDATE);
            return $article;
        }

        return new Article(['scenario' => Article::SCENARIO_CREATE]);
    }

    protected function saveArticleImage($imageUrl, Image $image)
    {
        $allowedTypes = [IMAGETYPE_BMP, IMAGETYPE_JPEG, IMAGETYPE_PNG];

        $imageValidator = new ImageValidator($allowedTypes);
        $tempDir = \Yii::getAlias('@app/runtime/tmp');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0775);
        }

        if (!is_writable($tempDir)) {
            chmod($tempDir, 0775);
        }

        $imageDownloader = new ImageDownloader($imageValidator, $tempDir);
        $filename        = uniqid('article_image') . '.' . pathinfo($imageUrl, PATHINFO_EXTENSION);
        $path            = $this->createDir((string) $image->id);
        $fullPathToSave  = $this->getFullImagePath() . $path;

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

        return $path . '/' . $filename;
    }

    protected function getFullImagePath()
    {
        return \Yii::getAlias('@app/web/' . $this->imagePath);
    }

    protected function createDir($id)
    {
        $maxFolders	    = 1000;
        $foldersLevel   = 3;
        $crc32Id	    = abs(crc32($id));
        $crc32AsFolders = (string) ($crc32Id % $maxFolders);
        $foldersLength  = strlen($crc32AsFolders);

        if ($foldersLength < $foldersLevel) {
            while ($foldersLength < $foldersLevel) {
                $crc32AsFolders = '0' . $crc32AsFolders;
                $foldersLength++;
            }
        }


        $folder = '';
        foreach (str_split($crc32AsFolders) as $folderFragment) {
            $folder .= '/' . $folderFragment;
        }

        $directory = $this->getFullImagePath() . $folder;

        if (file_exists($directory)) {
            return $folder;
        }

        if (!mkdir($directory, 0777, true)) {
            throw new \RuntimeException('Can\'t create directory: ' . $directory);
        }

        return $folder;
    }
}
