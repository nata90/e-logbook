<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\app\models\AppUser */
/* @var $form yii\widgets\ActiveForm */

$action = Yii::$app->controller->action->id;
?>

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

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <?php 

            if($action != 'update'){
                echo $form->field($model, 'password')->passwordInput(['maxlength' => true]); 
            }
            

            ?>

            <?= $form->field($model, 'active')->radioList(
                [0=>'Non Aktif', 1=>'Aktif'],
                []
            ) ?>

            <?= $form->field($model, 'id_group')->dropDownList(
                $list_group,
                ['prompt'=>'Pilih Salah Satu']
            )->label('Group') ?>

            <?= $form->field($model, 'pegawai_nama')->widget(\yii\jui\AutoComplete::classname(), [
                'options' => ['class' => 'form-control input-sm'],
                'clientOptions' => [
                    'source' => $data,
                    'minLength'=>'2', 
                    'autoFill'=>true,
                    'select' => new JsExpression("function( event, ui ) {
                        $('#appuser-pegawai_id').val(ui.item.id);
                     }")
                ],
            ]) ?>
            

            <?= $form->field($model, 'pegawai_id')->hiddenInput()->label(false) ?>

             <div class="box-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

</div>
