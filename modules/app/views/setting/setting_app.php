<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\app\models\AppUser */

$this->title = Yii::t('app', 'Setting App');

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
                    'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-5">{input}</div>',
                ]
            ]); ?>

            <?= Html::errorSummary($model, ['encode' => false, 'class'=>'callout callout-danger']) ?>

            <?= $form->field($model, 'mail_admin')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'tgl_periode_awal')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'tgl_periode_akhir')->textInput(['maxlength' => true]) ?>

            

             <div class="box-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>

    </div>

</div>

</div>