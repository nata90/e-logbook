<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\Bagian */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('var url = "' . Url::to(['bagian/getidbagian']) . '";');
$this->registerJs(<<<JS
    $(document).on("change", "#bagian-id_direktorat", function () {
        var direktorat = $(this).val();
        
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data:{'direktorat':direktorat},
            success: function(v){
                $('#bagian-id_bagian').val(v.id_bag);
            }
        });
    });
JS
);
?>

<div class="col-md-8">
    <div class="box box-danger">
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

            <?= $form->field($model, 'id_direktorat')->dropDownList(
                $listData,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>
            
            <?= $form->field($model, 'id_bagian')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'nama_bagian')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->radioList(
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
