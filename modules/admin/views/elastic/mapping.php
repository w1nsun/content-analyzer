<?php
/**
 * @var $mapping
 */
$this->title = Yii::t('app', 'Mapping');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Elastic'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="page-header"><?=$this->title?></h1>

<div class="well well-lg">
    <pre>
        <?=$mapping?>
    </pre>
</div>