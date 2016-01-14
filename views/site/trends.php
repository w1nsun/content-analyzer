<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\Article;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Тренды');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class'     => \yii\grid\DataColumn::className(),
                'attribute' => 'article.title',
                'format'    => 'html',
                'content'   => function($model) {
                    return
                        html::tag('div', Article::enumType($model->article->type), ['class' => 'atricle-type']) .
                        Html::a($model->article->title, $model->article->url) .
                        html::tag('div', Yii::$app->formatter->asDatetime($model->article->created_at));

                }
            ],
            'facebook',
            'twitter',
            'pinterest',
            'linkedin',
            'google_plus',
            'vkontakte',
            'total',
        ],
    ]); ?>

</div>
