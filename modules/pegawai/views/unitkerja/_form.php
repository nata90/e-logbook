<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\UnitKerja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?php echo $this->title;?>
            </h3>
        </div>

        <?php $form = ActiveForm::begin([
            'options'=>[
                'layout' => 'horizontal',
                'class'=>'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-8">{input}</div>',
            ]
        ]); ?>

        <div class="box-body">
            <?= $form->field($model, 'id_unit_kerja')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'id_bagian')->dropDownList(
                $listData,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'nama_unit_kerja')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status_unit')->textInput() ?>

            <?= $form->field($model, 'tmt_aktif')->textInput() ?>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
