<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Kinerja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kinerja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal_kinerja')->textInput() ?>

    <?= $form->field($model, 'id_pegawai')->textInput() ?>

    <?= $form->field($model, 'id_tugas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah')->textInput() ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'approval')->textInput() ?>

    <?= $form->field($model, 'user_approval')->textInput() ?>

    <?= $form->field($model, 'tgl_approval')->textInput() ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
