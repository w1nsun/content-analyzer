<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\RegisterForm */
/* @var $form ActiveForm */
?>
<div class="site-register_form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password') ?>
        <?= $form->field($model, 'repeat_password') ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-register_form -->
