<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grade-jabatan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_klp_jabatan')->textInput() ?>

    <?= $form->field($model, 'kode_grade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'grade')->textInput() ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nilai_jbt_max')->textInput() ?>

    <?= $form->field($model, 'nilai_jbt_min')->textInput() ?>

    <?= $form->field($model, 'nilai_jbt')->textInput() ?>

    <?= $form->field($model, 'status_grade')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
