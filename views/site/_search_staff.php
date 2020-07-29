<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>


<div class="kinerja-search">
    <?php $form = ActiveForm::begin([
                'options'=>[
                    'layout' => 'horizontal',
                    'class'=>'form-horizontal',
                    'data-pjax' => 1
                ],
                'fieldConfig' => [
                    'template' => '<label class="col-sm-4 control-label">{label}</label><div class="col-xs-6">{input}</div>',
                ],
                'action' => ['index'],
                'method' => 'get'
            ]); ?>
    <div class="form-group col-xs-5">
        <?= $form->field($model, 'tanggal_kinerja')->widget(\yii\jui\DatePicker::class,[
            'options'=>['class'=>'form-control'],
            'clientOptions' => ['changeYear' => true, 'changeMonth'=>true]
        ]) ?>
    </div>

    <div class="col-xs-1">
         <button type="submit" class="btn btn-block btn-success btn-sm">Cari</button>
     </div>


    <?php ActiveForm::end(); ?>
</div>

