<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Resource */

$this->title = Yii::t('app', 'Добавить Ресурс');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ресурс'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
