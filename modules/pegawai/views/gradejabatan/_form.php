<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatan */
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
            
            <?= $form->field($model, 'id_klp_jabatan')->dropDownList(
                $list_jabatan,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'kode_grade')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'grade')->textInput() ?>

            <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'nilai_jbt_min')->textInput() ?>

            <?= $form->field($model, 'nilai_jbt_max')->textInput() ?>

            <?= $form->field($model, 'nilai_jbt')->textInput() ?>

            <?= $form->field($model, 'status_grade')->radioList(
                [0=>'Non Aktif', 1=>'Aktif'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>
        </div>

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
