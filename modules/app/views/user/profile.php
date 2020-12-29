<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\app\models\AppUser */

$this->title = Yii::t('app', 'Profile');

?>
<div class="row">

    <div class="col-md-8">
    <div class="box box-danger box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?php echo $this->title;?>
            </h3>
        </div>

        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'options'=>[
                    'layout' => 'horizontal',
                    'class'=>'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-8">{input}</div>',
                ]
            ]); ?>

            <?= Html::errorSummary($model, ['encode' => false, 'class'=>'callout callout-danger']) ?>

            <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'tmp_lahir')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'pin')->textInput() ?>

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

            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'jam_masuk')->textInput() ?>

           <?= $form->field($model, 'gender')->radioList(
                [0=>'Pria', 1=>'Wanita'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Unit Kerja</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_unit->unitKerja->nama_unit_kerja==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_unit->unitKerja->nama_unit_kerja).'</span>';?></label>
                </div>
                
            </div>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Jabatan</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_jabatan->jabatan->nama_jabatan==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_jabatan->jabatan->nama_jabatan).'</span>';?></label>
                </div>
            </div>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Target</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_target->nilai_target==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_target->nilai_target).'</span>';?></label>
                </div>
            </div>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Penilai</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_jabatan->penilai->nama==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_jabatan->penilai->nama).'</span>';?></label>
                </div>
                
            </div>

             <div class="box-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>

    </div>

</div>

</div>