<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Resource */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resource-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropDownList(\app\models\Resource::enumType()) ?>

    <?= $form->field($model, 'lang')->dropDownList(Yii::$app->get('contentLanguage')->getList()) ?>

    <?= $form->field($model, 'country')->dropDownList(Yii::$app->get('contentCountry')->getList()) ?>

    <?= $form->field($model, 'status')->dropDownList(\app\models\Resource::enumStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Обновить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
