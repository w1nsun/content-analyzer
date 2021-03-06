<?php

namespace app\modules\api\controllers;

use app\models\Resource;
use yii\data\ActiveDataProvider;

class ResourceController extends Controller
{
    public $modelClass = 'app\models\Resource';

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
//        unset($actions['delete'], $actions['create']);
        unset($actions['index'], $actions['create'], $actions['update'], $actions['delete']);

        // customize the data provider preparation with the "prepareDataProvider()" method
//        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function actionIndex()
    {
        $provider = new ActiveDataProvider([
            'query' => Resource::find()->where(['status' => Resource::STATUS_ACTIVE]),
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
