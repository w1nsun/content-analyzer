<?php

use app\modules\admin\assets\Select2Asset;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tag */

Select2Asset::register($this);

$this->title = Yii::t('app', 'Тэги по категориям');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Тэги'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginForm() ?>

    <div class="form-group">
        <?= Html::dropDownList('tag_id', $tag_id, $tags, ['class' => 'form-control js-select-2'])?>
    </div>

    <div class="form-group">
        <?= Html::dropDownList('category_id', null, $categories, ['class' => 'form-control'])?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?= Html::endForm() ?>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.js-select-2').select2();
    });
</script>