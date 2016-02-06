<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
$this->title = \Yii::t('app', 'Профиль');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \yii\widgets\DetailView::widget([
    'model' => $user,
    'attributes' => [
        'id',
        'email',
        'access_token',
        'social_name',
        'social_id',
        'status',
    ],
]) ?>

<div class="well well-lg">
    <a href="<?=\yii\helpers\Url::to('/profile/generate-access-token');?>" class="btn btn-primary">
        <?=\Yii::t('app', 'Сгенерировать Access Token');?>
    </a>
</div>
