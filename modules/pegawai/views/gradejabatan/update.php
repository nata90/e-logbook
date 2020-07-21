<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatan */

$this->title = Yii::t('app', 'Update Grade Jabatan {name}', [
    'name' => $model->kode_grade,
]);
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'list_jabatan'=>$list_jabatan
    ]) ?>

</div>
