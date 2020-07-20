<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatan */

$this->title = Yii::t('app', 'Update Grade Jabatan: {name}', [
    'name' => $model->id_grade,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grade Jabatans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_grade, 'url' => ['view', 'id' => $model->id_grade]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="grade-jabatan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
