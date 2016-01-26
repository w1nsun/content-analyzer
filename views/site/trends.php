<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\Article;

/* @var $this yii\web\View */
/* @var $trends yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Тренды');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $trends,
        'columns' => [
            [
                'class'     => \yii\grid\DataColumn::className(),
                'attribute' => 'article.title',
                'format'    => 'html',
                'content'   => function(Article $model) {
                    return
                        html::tag('div', Article::enumType($model->type), ['class' => 'article-type']) .
                        Html::a($model->title, $model->url) .
                        html::tag('div', Yii::$app->formatter->asDatetime($model->created_at));

                }
            ],
            'likes_facebook',
            'likes_twitter',
            'likes_pinterest',
            'likes_linkedin',
            'likes_google_plus',
            'likes_vkontakte',
            'totalLikes',
        ],
    ]); ?>

</div>
