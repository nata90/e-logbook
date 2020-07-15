<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\UnitKerja */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('var url = "' . Url::to(['unitkerja/getidunit']) . '";');
$this->registerJs(<<<JS
    $(document).on("change", "#unitkerja-id_bagian", function () {
        var bagian = $(this).val();
        
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data:{'bagian':bagian},
            success: function(v){
                $('#unitkerja-id_unit_kerja').val(v.id_unit);
            }
        });
    });
JS
);
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
            
            <?= $form->field($model, 'id_bagian')->dropDownList(
                $listData,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'id_unit_kerja')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'nama_unit_kerja')->textInput(['maxlength' => true]) ?>


            <?= $form->field($model, 'status_unit')->radioList(
                [0=>'Non Aktif', 1=>'Aktif'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'tmt_aktif')->widget(\yii\jui\DatePicker::class,[
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
