<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grade-jabatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_grade') ?>

    <?= $form->field($model, 'id_klp_jabatan') ?>

    <?= $form->field($model, 'kode_grade') ?>

    <?= $form->field($model, 'grade') ?>

    <?= $form->field($model, 'deskripsi') ?>

    <?php // echo $form->field($model, 'nilai_jbt_max') ?>

    <?php // echo $form->field($model, 'nilai_jbt_min') ?>

    <?php // echo $form->field($model, 'nilai_jbt') ?>

    <?php // echo $form->field($model, 'status_grade') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
