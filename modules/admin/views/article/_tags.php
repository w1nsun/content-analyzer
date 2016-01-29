<?php
/**
 * @var $model \app\models\Article
 * @var $tag \app\models\Tag
 */
?>
<?php foreach($model->getTags() as $tag):?>
    <span class="label label-<?=($tag->category_id == 0) ? 'warning' : 'success';?>">
        <?=$tag->tag;?>
    </span>&nbsp;
<?php endforeach;?>
