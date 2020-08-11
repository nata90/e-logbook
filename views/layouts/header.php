<?php
use yii\helpers\Html;
use app\modules\app\models\AppUser;
use app\modules\pegawai\models\JabatanPegawai;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-lg">E-logbook</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <?php 
    $id_user = Yii::$app->user->id;
    $model = AppUser::findOne($id_user);
    $jab_pegawai = JabatanPegawai::find()->where(['id_pegawai'=>$model->pegawai_id, 'status_jbt'=>1])->one();
    if($jab_pegawai != null){
        $jabatan = $jab_pegawai->jabatan->nama_jabatan;
    }else{
        $jabatan = '-';
    }
    ?>


    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">

                    <a href="" class="dropdown-toggle" data-toggle="dropdown"><span class="hidden-xs"><?php echo $model->pegawai->nama;?> ( <?php echo $jabatan;?> )</span></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
