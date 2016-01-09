<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tags');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tag'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'tag',
            'category_id' => [
                'label' => \Yii::t('app', 'Категория'),
                'format' => 'raw',
                'value' => function ($data) use ($categories) {
                    /** @var app\models\Tag $data */
                    return Html::dropDownList(
                        "[item_category][{$data->id}]",
                        $data->category_id,
                        $categories,
                        ['class' => 'js_select_category']
                    );
                }
            ],
            'category_id',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
