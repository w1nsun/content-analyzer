<?php
use app\components\Roles;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
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
                <a class="navbar-brand" href="<?=Yii::$app->homeUrl;?>"><?=Yii::$app->params['projectName'];?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <?php
                $nav_sub_items = [];

                if (Yii::$app->user->can(Roles::ADMIN)) {
                    $nav_sub_items[] = [
                        'label'       => Yii::t('app', 'Админка'),
                        'url'         => ['/admin'],
                    ];
                }

                if (!Yii::$app->user->isGuest) {
                    $nav_sub_items[] = [
                        'label'       => Yii::t('app', 'Профиль'),
                        'url'         => ['/profile'],
                    ];
                    $nav_sub_items[] = [
                        'label'       => Yii::t('app', 'Выйти ({user})', ['user' => Yii::$app->user->identity->email]),
                        'url'         => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ];
                }
                ?>
                <?= Nav::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'nav navbar-nav navbar-right nav-pills'],
                    'items' => [
                        ['label' => Yii::t('app', 'Тренды'), 'url' => ['/site/trends']],
                        ['label' => Yii::t('app', 'Контакты'), 'url' => ['/site/contact']],
                        [
                            'label' => '<i class="glyphicon glyphicon-cog"></i> ' . Yii::t('app', 'Панель управления'),
                            'items' => $nav_sub_items,
                            'visible' => !Yii::$app->user->isGuest
                        ],
                        [
                            'label'   => Yii::t('app', 'Регистрация'),
                            'url'     => ['/site/signup'],
                            'visible' => Yii::$app->user->isGuest
                        ],
                        [
                            'label'   => Yii::t('app', 'Войти'),
                            'url'     => ['/site/login'],
                            'visible' => Yii::$app->user->isGuest
                        ]
                    ],
                ])
                ?>

            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <?php if($flashes = Yii::$app->session->getAllFlashes()):?>
            <?php foreach($flashes as $flashType => $message):?>
                <div class="alert alert-<?=$flashType;?>" role="alert"><?=$message;?></div>
            <?php endforeach;?>
        <?php endif;?>


        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
