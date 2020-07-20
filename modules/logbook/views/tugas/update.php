<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Tugas */

$this->title = Yii::t('app', 'Update Tugas {name}', [
    'name' => $model->nama_tugas,
]);
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'list_unit_kerja'=>$list_unit_kerja,
        'list_kategori'=>$list_kategori
    ]) ?>

</div>
