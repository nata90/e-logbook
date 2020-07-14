<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\DataPegawai */
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
            <?= Html::errorSummary($model, ['encode' => false]) ?>
            
            <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'pin')->textInput() ?>

            <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tmp_lahir')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tgl_lahir')->widget(\yii\jui\DatePicker::class,[
                        'options'=>['class'=>'form-control'],
                        'clientOptions' => ['changeYear' => true, 'changeMonth'=>true]
                    ]) ?>

            <?= $form->field($model, 'jenis_peg')->dropDownList(
                [0=>'PNS', 1=>'NON PNS', 2=>'Kontrak'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'status_peg')->dropDownList(
                [0=>'Masih Bekerja', 1=>'Pensiun', 2=>'Pindah ke Luar RS',3=>'Meninggal'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'gender')->radioList(
                [0=>'Pria', 1=>'Wanita'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>
        </div>
        

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
