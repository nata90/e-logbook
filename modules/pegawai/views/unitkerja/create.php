<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\UnitKerja */

$this->title = Yii::t('app', 'Buat Unit Kerja');
?>
<div class="row">
    <?= $this->render('_form', [
        'model' => $model,
        'listData' => $listData
    ]) ?>

</div>
