<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
Html::cssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/uba_ico.svg', ['alt' => Yii::$app->name, 'style' => 'height: 100px; left: 100px']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md', 'style' => 'height:100px:background-color:white;']
    ]);
    if (!Yii::$app->user->isGuest):
        ?>
        <div style="display: flex;flex: auto;flex-direction: column-reverse;">
        <?= Nav::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav','style' => 'display: flex;flex-direction: row-reverse;',],            
            'items' => [
                '<li class="nav-item">'
                . Html::beginForm(['/site/logout'])
                . Html::submitButton(
                    'Cerrar sesión (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'nav-link btn btn-link logout', 'style' => 'color:black;display: flex;flex-direction: row-reverse;']
                )
                . Html::endForm()
                . '</li>',
                [
                    'label' => '<p style="margin-bottom:0;color:black">Páginas</p>',
                    'visible' => !Yii::$app->user->isGuest,
                    'items' => [
                        ['label' => 'Mi perfil', 'url' => ['/profile']],
                        ['label' => 'Llamados a concursos', 'url' => ['/concurso']],
                        ['label' => 'Mis concursos', 'url' => ['/concurso/tramite']],
                        ['label' => 'Datos UUAA', 'url' => ['/site/datos-utiles']],
                        ['label' => 'Doc. a presentar', 'url' => ['/site/documentos']],
                    ],
                ],
            ]
        ])?>
        </div>
    <?php
    endif;
    
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main" style="margin:0 auto;">
    <div class="container pt-0" >
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
