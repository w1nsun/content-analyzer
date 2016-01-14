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
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->params['projectName'],
                'brandUrl'   => Yii::$app->homeUrl,
                'options'    => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
        ?>
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
                    'label'       => Yii::t('app', 'Выйти ({user})', ['user' => Yii::$app->user->identity->email]),
                    'url'         => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
        ?>
        <?= Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => Yii::t('app', 'Тренды'), 'url' => ['/site/trends']],
                ['label' => Yii::t('app', 'Контакты'), 'url' => ['/site/contact']],
                [
                    'label' => '<i class="glyphicon glyphicon-cog"></i> ' . Yii::t('app', 'Панель управления'),
                    'items' => $nav_sub_items,
                    'visible' => !Yii::$app->user->isGuest
                ],
                [
                    'label'   => Yii::t('app', 'Войти'),
                    'url'     => ['/site/login'],
                    'visible' => Yii::$app->user->isGuest
                ]
            ],
        ])
        ?>
        <?php NavBar::end()?>
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
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
