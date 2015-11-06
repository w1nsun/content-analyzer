<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            'id',
            'email:email',

            'access_token',
            [
                'attribute' => 'status',
                'filter'    => User::enumStatus(),
                'value'     => function ($data) {
                    return User::enumStatus($data->status);
                },

            ],
            [
                'label' => Yii::t('app', 'Роли'),
                'value' => function ($data) {
                    $roles = Yii::$app->authManager->getRolesByUser($data->id);
                    $tmp   = [];
                    foreach ($roles as $role) {
                        $tmp[] = $role->name;
                    }

                    return implode(', ', $tmp);
                },
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>

</div>
