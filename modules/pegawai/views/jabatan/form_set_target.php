<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
?>

<div>
	<?php $form = ActiveForm::begin([
            'options'=>[
                'layout' => 'horizontal',
                'class'=>'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-8">{input}</div>',
            ]
    ]); ?>

    <?= $form->field($model, 'status_jbt')->dropDownList(
                [0=>'Non Aktif', 1=>'Aktif'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>
    <?= $form->field($model, 'nilai_target')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'id_jabatan')->hiddenInput(['maxlength' => true])->label(false) ?>
    <?= $form->field($model, 'id_unit_kerja')->hiddenInput(['maxlength' => true])->label(false) ?>
    
    
    <?php ActiveForm::end(); ?>
</div>