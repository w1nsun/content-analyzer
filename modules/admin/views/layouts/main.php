<?php
use app\modules\admin\assets\AdminAsset;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only"><?=Yii::t('app', 'Навигация');?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><?=Yii::$app->params['projectName'];?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <?=Nav::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'nav navbar-nav navbar-right nav-pills'],
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Статистика'),
                            'url'   => ['/admin/dashboard/index'],
                        ],
                        [
                            'label'       => Yii::t('app', 'Выйти ({user})', ['user' => Yii::$app->user->identity->email]),
                            'url'         => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ],
                    ],
                ])
                ?>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">

                <?=Nav::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'nav nav-sidebar'],
                    'items' => [
                        [
                            'label'       => Yii::t('app', 'Ресурсы'),
                            'url'         => ['/admin/resource/index'],
                        ],
                        [
                            'label'       => Yii::t('app', 'Статьи'),
                            'url'         => ['/admin/article/index'],
                        ],
                        [
                            'label'       => Yii::t('app', 'Категории'),
                            'url'         => ['/admin/category/index'],
                        ],
                        [
                            'label'       => Yii::t('app', 'Тэги'),
                            'url'         => ['/admin/tag/index'],
                        ],
                        [
                            'label'       => Yii::t('app', 'Пользователи'),
                            'url'         => ['/admin/user/index'],
                        ],
                    ],
                ])
                ?>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= $content ?>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>