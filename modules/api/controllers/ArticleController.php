<?php

namespace app\modules\api\controllers;

use app\models\Article;
use yii\data\ActiveDataProvider;

class ArticleController extends Controller
{
    public $modelClass = 'app\models\Article';

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
        $article = $this->loadByUrlOrNew(\Yii::$app->request->post('Article')['url']);

        if ($article->load(\Yii::$app->request->post())) {

            if($article->getIsNewRecord()){
                $article->type = Article::TYPE_ARTICLE;
                $article->status = Article::STATUS_ACTIVE;
            }

            if ($article->save()) {
                return ['id' => $article->id];
            }
        }

        \Yii::$app->response->setStatusCode(500);
        \Yii::$app->response->statusText ='Internal Server Error';
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
}
