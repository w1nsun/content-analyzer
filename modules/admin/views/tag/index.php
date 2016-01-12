<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $changeCategoryUrl string */
/* @var $categories array */

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
        'options' => [
            'class' => 'js_grid_tags'
        ],
        'columns' => [
            'id',
            'tag',
            'category_id' => [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($data) use ($categories) {
                    /** @var app\models\Tag $data */
                    return Html::dropDownList(
                        "tag_id_{$data->id}",
                        $data->category_id,
                        array_merge(['0' => ''], $categories),
                        ['class' => 'js_select_category form-control']
                    );
                },
                'filter' => $categories
            ],
            'status' => [
                'attribute' => 'status',
                'filter' => $enumTagStatus
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<script>
    var changeTagCategoryUrl = '<?=$changeCategoryUrl;?>';
    $('.js_grid_tags').on('change', '.js_select_category', function () {
        var tagId = $(this).attr('name').replace('tag_id_', '');
        var categoryId = $(this).val();

        $.post(
            changeTagCategoryUrl,
            {'tag_id' : tagId, 'category_id' : categoryId},
            function (response) {
                if (response.result == 'ok') {
                    alert('Saved');
                }
            },
            'json'
        );
    });
</script>
