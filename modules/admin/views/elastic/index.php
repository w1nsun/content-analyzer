<?php
use \yii\helpers\Url;

$this->title = Yii::t('app', 'Elastic');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-header"><?=$this->title?></h1>
<div class="list-group">
    <a href="<?=Url::to('/admin/elastic/mapping')?>" class="list-group-item"><?=Yii::t('app', 'Mapping');?></a>
</div>
