<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\DataPegawai */

$this->title = Yii::t('app', 'Update data pegawai {name}', [
    'name' => $model->nama,
]);
?>
<div class="row">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
