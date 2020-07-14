<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\UnitKerja */

$this->title = Yii::t('app', 'Update Unit Kerja: {name}', [
    'name' => $model->id_unit_kerja,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Unit Kerjas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_unit_kerja, 'url' => ['view', 'id' => $model->id_unit_kerja]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="unit-kerja-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
