<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\KlpJabatan */
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
                'template' => '<label class="col-sm-4 control-label">{label}</label><div class="col-xs-7">{input}</div>',
            ]
        ]); ?>

	    <div class="box-body">
	    	<?= Html::errorSummary($model, ['encode' => false, 'class'=>'callout callout-danger']) ?>

		    <?= $form->field($model, 'kode_klp_jabatan')->textInput(['maxlength' => true]) ?>

		    <?= $form->field($model, 'nama_klp_jabatan')->textInput(['maxlength' => true]) ?>

		    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

		    <?= $form->field($model, 'status_klp_jabatan')->radioList(
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
