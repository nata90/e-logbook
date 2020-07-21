<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatan */

$this->title = Yii::t('app', 'Create Grade Jabatan');

?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'list_jabatan'=>$list_jabatan
    ]) ?>

</div>
