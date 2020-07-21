<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\Jabatan */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="col-md-8">
    <div class="box box-danger box-solid">
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
            <?= Html::errorSummary($model, ['encode' => false, 'class'=>'callout callout-danger']) ?>

            <?= $form->field($model, 'id_grade')->dropDownList(
                $list_grade,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>


            <?= $form->field($model, 'nama_jabatan')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'level_jabatan')->dropDownList(
                $list_level,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'peer_grup')->dropDownList(
                $list_peer,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'status_jabatan')->radioList(
                [0=>'Non Aktif', 1=>'Aktif'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'tmt_jabatan')->widget(\yii\jui\DatePicker::class,[
                        'options'=>['class'=>'form-control'],
                        'clientOptions' => ['changeYear' => true, 'changeMonth'=>true]
                    ]) ?>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
