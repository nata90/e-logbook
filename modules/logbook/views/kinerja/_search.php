<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\KinerjaSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('var url_search = "' . Url::to(['kinerja/searchkinerja']) . '";');
$this->registerJs(<<<JS

JS
);

?>



    <?php $form = ActiveForm::begin([
                'options'=>[
                    'layout' => 'horizontal',
                    'class'=>'form-horizontal',
                    'data-pjax' => 1
                ],
                'fieldConfig' => [
                    //'template' => '<label class="col-sm-2 control-label">{label}</label><div class="col-xs-8">{input}</div>',
                    'template' => '{input}',
                ],
                'action' => ['index'],
                'method' => 'get'
            ]); ?>

    <div class="form-group col-xs-4">
        <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <?php /*<input type="text" id="reservation" class="form-control pull-right" name="KinerjaSearch[range_date]">*/ ?>
              <?= $form->field($model, 'range_date',['options'=>['tag' => false]])->textInput(['class'=>'form-control pull-right','id'=>'reservation'])->label(false) ?>
        </div>
    </div>
    <div class="form-group col-xs-4">
        <label class="col-sm-2 control-label"></label>
        <div class="col-xs-8">
        <?= $form->field($model, 'id_pegawai')->dropDownList(
                $listPegawai,
                [
                    'prompt'=>'Pilih Salah Satu',
                ]
            )->label(false) ?>
        </div>
    </div>
    <div class="col-xs-1">
         <button type="submit" class="btn btn-block btn-success btn-sm" id="search-kinerja">Cari</button>
     </div>


    <?php ActiveForm::end(); ?>

