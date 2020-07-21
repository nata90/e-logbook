<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\Jabatan */

$this->title = Yii::t('app', 'Buat Jabatan');
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'list_grade'=>$list_grade,
        'list_level'=>$list_level,
        'list_peer'=>$list_peer
    ]) ?>

</div>
