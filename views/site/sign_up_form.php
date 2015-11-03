<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\forms\RegisterForm */
/* @var $form ActiveForm */

$this->title = \Yii::t('app', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signup_form">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'email')->input('email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'repeat_password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Зарегистрироватся'), ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div><!-- site-register_form -->
