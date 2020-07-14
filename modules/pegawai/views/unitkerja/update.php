<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\UnitKerja */

$this->title = Yii::t('app', 'Update Unit Kerja {name}', [
    'name' => $model->nama_unit_kerja,
]);
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'listData'=>$listData
    ]) ?>

</div>
