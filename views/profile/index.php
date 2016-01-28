<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
$this->title = \Yii::t('app', 'Профиль');
$this->params['breadcrumbs'][] = $this->title;
?>
<div>ID: <?= $user->id;?></div>
<div>Email: <?= $user->email;?></div>
<div>Token: <?= $user->access_token;?></div>
<div>Social: <?= $user->social_name;?></div>
<div>Social ID: <?= $user->social_id;?></div>