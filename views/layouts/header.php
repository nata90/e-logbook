<?php
use yii\helpers\Html;
use app\modules\app\models\AppUser;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-lg">E-logbook</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <?php 
    $id_user = Yii::$app->user->id;
    $model = AppUser::findOne($id_user);
    ?>


    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">

                    <a href="" class="dropdown-toggle" data-toggle="dropdown"><span class="hidden-xs"><?php echo $model->pegawai->nama;?></span></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
