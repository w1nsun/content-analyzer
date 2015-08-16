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
}
