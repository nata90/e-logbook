<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use edwinhaq\simpleloading\SimpleLoading;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-red sidebar-mini">
        <audio id="myAudio">
          <source src="<?php echo Yii::$app->request->baseUrl.'/notifikasi/notifikasi.mp3'?>" type="audio/ogg">
          <source src="<?php echo Yii::$app->request->baseUrl.'/notifikasi/notifikasi.ogg'?>" type="audio/mpeg">
          Your browser does not support the audio element.
        </audio>
         <?php 
            Modal::begin([
                    'header'=>'<span id="header-info"></span>',
                    'id'=>'modal',
                    'size'=>'modal-md',
                    'footer'=>'<span id="footer-info"></span>'
                ]);
            echo "<div id='modalContent'></div>";
            Modal::end();
        ?>
    <?php $this->beginBody() ?>
    <?php SimpleLoading::widget();?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
