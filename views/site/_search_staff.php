<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->registerJs('var daysago = "' . date('m/d/Y'). '";');
$this->registerJs('var daysnow = "' . date('m/d/Y') . '";');
$this->registerJs('var url_download = "' . Url::to(['site/excelrekap']) . '";');
$this->registerJs('var url_logbook = "' . Url::to(['site/excellogbook']) . '";');
$this->registerJs(<<<JS
    //Date range picker
    $('#reservation').daterangepicker();

    $(document).on("click", "#rekap-logbook", function () {

        window.open(url_download);
        
    });

    $(document).on("click", "#list-logbook", function () {

        window.open(url_logbook);
        
    });

    $(document).on("click", ".rekap-peg", function () {
        var url = $(this).attr('url');
        window.open(url);
        
    });

    $(document).on("click", ".logbook-peg", function () {
        var url = $(this).attr('url');
        window.open(url);
        
    });

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
              <?= $form->field($model, 'range_date',['options'=>['tag' => false]])->textInput(['class'=>'form-control pull-right','id'=>'reservation'])->label(false) ?>
        </div>
    </div>
    <div class="col-xs-2">
         <button type="submit" class="btn btn-block btn-success btn-sm btn-flat" id="search-kinerja">Cari</button>
     </div>
     <div class="col-xs-2">
         <button type="button" class="btn btn-block bg-olive btn-sm btn-flat" id="rekap-logbook">Rekap Per Kategori (.xls)</button>
     </div>
     <div class="col-xs-2">
         <button type="button" class="btn btn-block bg-maroon btn-sm btn-flat" id="list-logbook">Logbook (.xls)</button>
     </div>

<?php ActiveForm::end(); ?>

