<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\Bagian */

$this->title = Yii::t('app', 'Update Bagian {name}', [
    'name' => $model->nama_bagian,
]);
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'listData' => $listData
    ]) ?>

</div>
