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

    <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id')->hiddenInput(['maxlength' => true])->label(false) ?>
    
    <?php ActiveForm::end(); ?>
</div>