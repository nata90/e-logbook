<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\KlpJabatan */

$this->title = Yii::t('app', 'Update Kelompok Jabatan {name}', [
    'name' => $model->nama_klp_jabatan,
]);
?>
<div class="klp-jabatan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
