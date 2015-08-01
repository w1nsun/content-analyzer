<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \app\models\Resource;

/* @var $this yii\web\View */
/* @var $model app\models\Resource */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ресурсы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Редктировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены что хотите удалить этот элемент?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'url:ntext',
            [
                'attribute' => 'type',
                'value' => Resource::enumType($model->type),
            ],
            'last_run_time:datetime',
            [
                'attribute' => 'status',
                'value' => Resource::enumStatus($model->status),
            ],
        ],
    ]) ?>

</div>
