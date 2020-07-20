<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatan */

$this->title = Yii::t('app', 'Create Grade Jabatan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Grade Jabatans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grade-jabatan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
